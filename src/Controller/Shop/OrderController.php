<?php


namespace App\Controller\Shop;


use App\Controller\RouteName;
use App\Entity\Shop\Order\Order;
use App\Exception\Shop\Order\OrderHasNoProductException;
use App\Exception\Shop\Order\RefundFailedException;
use App\Exception\Shop\Order\StripeApiException;
use App\Repository\Shop\LocaleRepository;
use App\Services\Shop\Order\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/shop/order')]
class OrderController extends AbstractController
{
    /**
     * @var OrderService
     */
    private OrderService $orderService;
    private LocaleRepository $localeRepository;

    public function __construct(OrderService $orderService, LocaleRepository $localeRepository)
    {
        $this->orderService = $orderService;
        $this->localeRepository = $localeRepository;
    }

    #[Route('/{id}', name: RouteName::ORDER)]
    public function order(Order $order, Request $request): Response
    {
        $token = $request->query->get('token');
        if ($token !== $order->getToken()) {
            throw new NotFoundHttpException();
        }
        if (!RouteName::checkAuthorizedLocales($this->localeRepository->findAll(), $request->getLocale())) {
            throw new NotFoundHttpException();
        }
        try {
            if ($request->query->get('session_id') && $order->getState() === 0) {
                $sessionId = $request->query->get('session_id');
                $paymentSucceed = $this->orderService->verifyPaymentStripeApi($order, $sessionId);
                if ($paymentSucceed) {
                    // TODO : send an email to say that the order is payed
                    $this->addFlash(
                        RouteName::BASIC_SUCCESS,
                        'Votre paiement a bien été pris en compte, merci de votre confiance !'
                    );
                } else {
                    $this->addFlash(
                        RouteName::BASIC_ERROR,
                        'Votre paiement n\'a pas pu être vérifié, nous faisons notre possible pour résoudre ce problème'
                    );
                }
            }
        } catch (StripeApiException $e) {
            // TODO handle this error message in views
            $this->addFlash($e::ERROR_NAME, $e->errorMessage());
        }
        return $this->render('shop/order/order.html.twig', [
            'order' => $order,
            'orderState' => Order::STATE,
        ]);
    }

    #[Route('/cancel/{id}', name: RouteName::ORDER_CANCEL, methods: ['POST'])]
    public function cancelOrder(Order $order, Request $request): RedirectResponse
    {
        if (!RouteName::checkAuthorizedLocales($this->localeRepository->findAll(), $request->getLocale())) {
            throw new NotFoundHttpException();
        }
        if ($order->getState() <= 1 && $this->isCsrfTokenValid('delete' . $order->getId(), $request->request->get('_token'))) {
            try {
                if ($order->getState() === 1) {
                    $this->orderService->manageRefundStripeApi($order);
                } else {
                    $this->orderService->cancelOrder($order);
                    $this->getDoctrine()->getManager()->flush();
                }
                $this->addFlash('success', 'Votre commande a bien été annulée. Si vous aviez déjà payé vous serez remboursé(e) dans les 5 à 10 jours suivant l\'annulation');
            } catch (RefundFailedException|OrderHasNoProductException $e) {
                // TODO handle this error message in views
                $this->addFlash($e::ERROR_NAME, $e->errorMessage());
                return $this->redirect($this->generateUrl(RouteName::ORDER, ['id' => $order->getId()]) . RouteName::ORDER_TOKEN . $order->getToken());
            }
        }
        return $this->redirect($this->generateUrl(RouteName::ORDER, ['id' => $order->getId()]) . RouteName::ORDER_TOKEN . $order->getToken());
    }
}