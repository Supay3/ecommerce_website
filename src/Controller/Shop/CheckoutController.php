<?php

namespace App\Controller\Shop;

use App\Controller\RouteName;
use App\Entity\Shop\Order\Order;
use App\Exception\Shop\Cart\CartEmptyException;
use App\Exception\Shop\Order\NoShipmentException;
use App\Exception\Shop\Order\Notification\OrderNotificationFailedException;
use App\Exception\Shop\Order\OrderDoNotExistException;
use App\Exception\Shop\Order\OrderHasNoProductException;
use App\Exception\Shop\Order\ProductAlreadySoldException;
use App\Exception\Shop\Order\StripeApiException;
use App\Form\Shop\Order\OrderAddressType;
use App\Form\Shop\Order\OrderShipmentType;
use App\Form\User\Checkout\OrderAddressType as OrderAddressAccountType;
use App\Notification\Shop\Order\OrderNotification;
use App\Repository\Shop\LocaleRepository;
use App\Repository\Shop\Order\AddressRepository;
use App\Repository\Shop\Product\ProductRepository;
use App\Repository\Shop\Shipment\ShipmentRepository;
use App\Repository\User\UserRepository;
use App\Services\Shop\Order\CartService;
use App\Services\Shop\Order\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

// TODO : l'adresse de facturation doit être la même que l'adresse de livraison dans une commande si pas de choix d'adresse de facturation
// TODO : refactor absolument, pour le addressWithAccount qui dispose d'une logique quasi-similaire à address
#[Route('/{_locale}/shop/checkout')]
class CheckoutController extends AbstractController
{
    /**
     * @var CartService
     */
    private CartService $cartService;

    /**
     * @var OrderService
     */
    private OrderService $orderService;
    private LocaleRepository $localeRepository;

    public function __construct(CartService $cartService, OrderService $orderService, LocaleRepository $localeRepository)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->localeRepository = $localeRepository;
    }

    #[Route('/address-account', name: RouteName::ADDRESS_WITH_ACCOUNT)]
    public function addressWithAccount(Request $request): RedirectResponse|Response
    {
        try {
            if (!RouteName::checkAuthorizedLocales($this->localeRepository->findAll(), $request->getLocale())) {
                throw new NotFoundHttpException();
            }
            $this->cartService->checkCart();
            $orderSession = $request->getSession()->get('order');
            $order = $orderSession ?? (new Order())->setUser($this->getUser());
            $form = $this->createForm(OrderAddressAccountType::class, $order);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $order->setEmail($this->getUser()->getEmail());
                $request->getSession()->set('shippingAddress', $order->getShippingAddress());
                $order->setShippingAddress(null);
                $request->getSession()->set('order', $order);
                return $this->redirectToRoute(RouteName::CHECKOUT_SHIPMENT);
            }
        } catch (CartEmptyException $e) {
            $this->addFlash($e::ERROR_NAME, $e->errorMessage());
            return $this->redirectToRoute(RouteName::CART_INDEX);
        }
        return $this->render('user/checkout/address.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/address', name: RouteName::CHECKOUT_ADDRESS)]
    public function address(Request $request): Response
    {
        try {
            if (!RouteName::checkAuthorizedLocales($this->localeRepository->findAll(), $request->getLocale())) {
                throw new NotFoundHttpException();
            }
            $this->cartService->checkCart();
            $orderSession = $request->getSession()->get('order');
            $order = $orderSession ?? new Order();
            $form = $this->createForm(OrderAddressType::class, $order);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $request->getSession()->set('order', $order);
                return $this->redirectToRoute(RouteName::CHECKOUT_SHIPMENT);
            }
        } catch (CartEmptyException $e) {
            $this->addFlash($e::ERROR_NAME, $e->errorMessage());
            return $this->redirectToRoute(RouteName::CART_INDEX);
        }

        return $this->render('shop/checkout/address.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/shipment', name: RouteName::CHECKOUT_SHIPMENT)]
    public function shipment(Request $request): Response
    {
        try {
            if (!RouteName::checkAuthorizedLocales($this->localeRepository->findAll(), $request->getLocale())) {
                throw new NotFoundHttpException();
            }
            $this->cartService->checkCart();
            $order = $request->getSession()->get('order');
            $order = $order ?? throw new OrderDoNotExistException();

            $form = $this->createForm(OrderShipmentType::class, $order);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $request->getSession()->set('shipment', $order->getShipment()->getId());
                $order->setShipment(null);
                $request->getSession()->set('order', $order);
                return $this->redirectToRoute(RouteName::CHECKOUT_SUMMARY);
            }

        } catch (CartEmptyException|OrderDoNotExistException $e) {
            $this->addFlash($e::ERROR_NAME, $e->errorMessage());
            return $this->redirectToRoute(RouteName::CART_INDEX);
        }
        return $this->render('shop/checkout/shipment.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/summary', name: RouteName::CHECKOUT_SUMMARY)]
    public function summary(
        Request $request,
        ProductRepository $productRepository,
        ShipmentRepository $shipmentRepository,
        AddressRepository $addressRepository,
        UserRepository $userRepository,
    ): Response
    {
        try {
            if (!RouteName::checkAuthorizedLocales($this->localeRepository->findAll(), $request->getLocale())) {
                throw new NotFoundHttpException();
            }
            $this->cartService->checkCart();
            $order = $request->getSession()->get('order');
            $shipmentSession = $request->getSession()->get('shipment');
            $order = $order ?? throw new OrderDoNotExistException();

            // TODO : refactor
            if (!$order->getShippingAddress() && $request->getSession()->has('shippingAddress')) {
                $shippingAddress = $addressRepository->find($request->getSession()->get('shippingAddress'));
                $order->setShippingAddress($shippingAddress);
            }

            $shipment = $shipmentSession ? $shipmentRepository->find($request->getSession()->get('shipment')) : throw new NoShipmentException();
            $cartProducts = $this->cartService->getFullCart($productRepository, $request->getLocale());
            $total = $this->cartService->getTotalFromCart($cartProducts);
            $totalShipment = $shipment->getPrice();
            $totalWithShipment = $total + $shipment->getPrice();
        } catch (CartEmptyException|NoShipmentException|OrderDoNotExistException $e) {
            $this->addFlash($e::ERROR_NAME, $e->errorMessage());
            if ($e instanceof NoShipmentException) {
                return $this->redirectToRoute(RouteName::CHECKOUT_SHIPMENT);
            }
            return $this->redirectToRoute(RouteName::CART_INDEX);
        }
        return $this->render('shop/checkout/summary.html.twig', [
            'cartProducts' => $cartProducts,
            'order' => $order,
            'total' => $total,
            'totalShipment' => $totalShipment,
            'totalWithShipment' => $totalWithShipment,
        ]);
    }

    #[Route('/validate', name: RouteName::CHECKOUT_VALIDATION)]
    public function validate(
        Request $request,
        ProductRepository $productRepository,
        ShipmentRepository $shipmentRepository,
        OrderNotification $orderNotification,
        AddressRepository $addressRepository
    ): Response
    {
        $order = $request->getSession()->get('order');
        $shipment = $request->getSession()->get('shipment');

        // TODO : REFACTOR
        if ($this->isGranted('ROLE_USER') && $request->getSession()->has('shippingAddress')) {
            $shippingAddress = $addressRepository->find($request->getSession()->get('shippingAddress'));
            $order->setShippingAddress($shippingAddress);
            $shippingAddress->setUser($this->getUser());
            $order->setUser($this->getUser());
        }

        try {
            $order = $order ?? throw new OrderDoNotExistException();
            $shipment = $shipment ? $shipmentRepository->find($request->getSession()->get('shipment')) : throw new NoShipmentException();
            $this->cartService->checkCart();
            $cartProducts = $this->cartService->getFullCart($productRepository);
            $entityManager = $this->getDoctrine()->getManager();

            $this->orderService->addProductsSold($cartProducts, $order);
            $this->orderService->verifyOrderProducts($order);
            $this->orderService->createOrder($order, $shipment, $request->getLocale());

            $entityManager->persist($order);
            $entityManager->flush();

            $request->getSession()->remove('order');
            $request->getSession()->remove('shipment');

            $this->cartService->removeAllCart();
            $orderNotification->notifyOrderValidation($order);
        } catch (
            OrderHasNoProductException
            |ProductAlreadySoldException
            |CartEmptyException
            |NoShipmentException
            |OrderDoNotExistException
            |OrderNotificationFailedException $e
        ) {
            $this->addFlash($e::ERROR_NAME, $e->errorMessage());
            if ($e instanceof NoShipmentException) {
                return $this->redirectToRoute(RouteName::CHECKOUT_SHIPMENT);
            }
            if ($e instanceof OrderNotificationFailedException) {
                if ($this->isGranted('ROLE_USER')) {
                    return $this->redirectToRoute(RouteName::USER_ORDER, [
                        'id' => $order->getId(),
                        'number' => $order->getNumber()
                    ]);
                }
                return $this->redirect($this->generateUrl(RouteName::ORDER, ['id' => $order->getId()]) . RouteName::ORDER_TOKEN . $order->getToken());
            }

            return $this->redirectToRoute(RouteName::CART_INDEX);
        }
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute(RouteName::USER_ORDER, [
                'id' => $order->getId(),
                'number' => $order->getNumber()
            ]);
        }
        return $this->redirect($this->generateUrl(RouteName::ORDER, ['id' => $order->getId()]) . RouteName::ORDER_TOKEN . $order->getToken());
    }

    #[Route('/payment-{id}', name: RouteName::CHECKOUT_PAY, methods: ['POST'])]
    public function payOrder(Order $order, Request $request, OrderNotification $orderNotification): JsonResponse
    {
        $token = $request->query->get('token');
        if ($token !== $order->getToken() || $order->getState() >= 1 || !$this->isCsrfTokenValid('pay-order' . $order->getId(), $request->getContent())) {
            throw new NotFoundHttpException();
        }
        try {
            $response = $this->orderService->setStripeApiPayment($order);
            $orderNotification->notifyOrderPayment($order);
            return $response;
        } catch (StripeApiException|OrderNotificationFailedException $e) {
            if ($e instanceof OrderNotificationFailedException) {
                $this->addFlash($e::ERROR_NAME, $e->errorMessage());
                return $response;
            }
            throw new NotFoundHttpException($e->errorMessage());
        }
    }

    #[Route('/remove_billing', name: RouteName::CHECKOUT_REMOVE_BILLING_ADDRESS, methods: ['POST'])]
    public function removeBillingAddress(Request $request): JsonResponse
    {
        if (!$this->isCsrfTokenValid('remove-billing-address', $request->getContent())) {
            throw new NotFoundHttpException();
        }
        $order = $request->getSession()->get('order');
        if ($order && $order->getBillingAddress()) {
            $order->setBillingAddress(null);
            return new JsonResponse();
        }
        return new JsonResponse('Unauthorized');
    }
}
