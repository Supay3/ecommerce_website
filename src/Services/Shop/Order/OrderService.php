<?php


namespace App\Services\Shop\Order;


use App\Controller\RouteName;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\Order\OrderNumber;
use App\Entity\Shop\Order\ProductSold;
use App\Entity\Shop\Shipment\Shipment;
use App\Exception\Shop\Order\OrderHasNoProductException;
use App\Exception\Shop\Order\ProductAlreadySoldException;
use App\Exception\Shop\Order\RefundFailedException;
use App\Exception\Shop\Order\StripeApiException;
use App\Repository\Shop\Order\OrderNumberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Refund;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrderService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var OrderNumberRepository $numberRepository The number repository where the Number of the Order is stocked
     */
    private OrderNumberRepository $numberRepository;

    /**
     * @var string $stripeKey The stripeApiKey defined in the .env
     */
    private string $stripeKey;

    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    public function __construct(
        EntityManagerInterface $entityManager,
        OrderNumberRepository $numberRepository,
        UrlGeneratorInterface $urlGenerator,
        RequestStack $requestStack,
        string $stripeKey
    )
    {
        $this->entityManager = $entityManager;
        $this->numberRepository = $numberRepository;
        $this->stripeKey = $stripeKey;
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    /**
     * Manages the Order by adding the total items sold, the total prices, the Shipment, the state, the token and the locale
     *
     * @param Order $order The current Order
     * @param Shipment $shipment The current Shipment the Customer has chosen
     * @param string $locale The current locale in the format 'fr_FR' for example
     * @throws ProductAlreadySoldException if the Product has no stock
     */
    public function createOrder(Order $order, Shipment $shipment, string $locale)
    {
        $productsSold = [];
        try {
            foreach ($order->getProductsSold() as $item) {
                if ($item->getProduct()->getStock() >= $item->getQuantity() && $item->getQuantity() !== 0) {
                    $this->removeProductStock($item);
                    $productsSold[] = $item;
                    $order
                        ->setTotal($order->getTotal() + ($item->getProduct()->getPrice() * $item->getQuantity()))
                        ->setItemsTotal($order->getItemsTotal() + $item->getQuantity())
                    ;
                } else {
                    $itemTranslation = 'Undefined';
                    foreach ($item->getProduct()->getProductTranslations() as $productTranslation) {
                        if ($productTranslation->getLocale()->getCode() === $this->requestStack->getCurrentRequest()->getLocale()) {
                            $itemTranslation = $productTranslation;
                        }
                    }
                    throw new Exception($itemTranslation);
                }
            }
            $order
                ->setToken($this->generateOrderToken())
                ->setState(0)
                ->setLocaleCode($locale)
                ->setShipment($shipment)
                ->setTotalWithShipment($order->getTotal() + $order->getShipment()->getPrice())
            ;
        } catch (Exception $e) {
            $this->cancelOrderBeforePersist($order, $productsSold);
            throw new ProductAlreadySoldException($e->getMessage());
        }
        $order->setNumber(date('Ymd') . $this->generateOrderNumber());
    }

    /**
     * Add productsSold from the CartService
     *
     * @param array $cartProducts The products that where in the cart
     * @param Order $order The current Order
     */
    public function addProductsSold(array $cartProducts, Order $order)
    {
        foreach ($cartProducts as $cartProduct) {
            $productSold = (new ProductSold())
                ->setQuantity($cartProduct['quantity'])
                ->setProduct($cartProduct['item'])
                ->setOrderList($order)
            ;
            $order->addProductsSold($productSold);
        }
    }

    /**
     * Verify if the Order has Products, if not, throws an Exception
     *
     * @param Order $order The current Order
     * @throws OrderHasNoProductException if the Order has no Product
     */
    public function verifyOrderProducts(Order $order)
    {
        if (!$order->getProductsSold()->isEmpty()) {
            return;
        }
        throw new OrderHasNoProductException();
    }

    /**
     * Manages the ProductSold by removing the quantity sold from the Product stock
     *
     * @param ProductSold $productSold
     * @throws ProductAlreadySoldException
     */
    public function removeProductStock(ProductSold $productSold)
    {
        if ($productSold->getProduct()->getStock() >= $productSold->getQuantity() && $productSold->getQuantity() !== 0) {
            $productSold->getProduct()->setStock($productSold->getProduct()->getStock() - $productSold->getQuantity());
        } else {
            throw new ProductAlreadySoldException($productSold->getProduct());
        }
        $this->entityManager->flush();
    }

    /**
     * Sets the StripeApi basics with the current Order
     *
     * @param Order $order The current Order
     * @return JsonResponse If success returns the PaymentIntent
     * @throws StripeApiException If the api doesn't works
     */
    public function setStripeApiPayment(Order $order): JsonResponse
    {
        try {
            Stripe::setApiKey(
                $this->stripeKey,
            );
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Commande n°' . $order->getNumber(),
                        ],
                        'unit_amount' => number_format($order->getTotalWithShipment(), 2, '', ''),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->urlGenerator->generate(RouteName::ORDER, ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL) . RouteName::ORDER_TOKEN . $order->getToken() .  RouteName::ORDER_SESSION_ID,
                'cancel_url' => $this->urlGenerator->generate(RouteName::ORDER, ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL) . RouteName::ORDER_TOKEN . $order->getToken() . RouteName::ORDER_SESSION_ID,
            ]);
            return new JsonResponse(['id' => $session->id]);
        } catch (ApiErrorException $e) {
            return new JsonResponse($e->getMessage());
            //throw new StripeApiException($e->getMessage());
        }
    }

    /**
     * Make a call the the StripeApi and try to create a refund for the client
     *
     * @param Order $order The current Order
     * @throws OrderHasNoProductException If the order has no ProductsSold
     * @throws RefundFailedException If the refund failed in the StripeApi
     */
    public function manageRefundStripeApi(Order $order)
    {
        try {
            if ($order->getState() >= 1 && $order->getState() !== 6 && $order->getPaymentIntent()) {
                Stripe::setApiKey($this->stripeKey);
                $refund = Refund::create([
                    'payment_intent' => $order->getPaymentIntent(),
                    'reason' => 'requested_by_customer',
                ]);
                if ($refund->status === 'succeeded') {
                    $order->setState(6);
                } else {
                    $order->setState(5);
                    throw new Exception();
                }
            } else {
                $order->setState(5);
            }
            $this->cancelOrder($order);
            $this->entityManager->flush();
        } catch (Exception $e) {
            if ($e instanceof ApiErrorException) {
                throw new RefundFailedException();
            }
            throw new OrderHasNoProductException();
        }
    }

    /**
     * Verify if the payment is a success, only use this method
     * if the state of the Order is unpaid
     *
     * @param Order $order The current Order
     * @param string $sessionId The session id passed by the api in the URI
     * @throws StripeApiException If the api throws an Exception
     */
    public function verifyPaymentStripeApi(Order $order, string $sessionId): bool
    {
        try {
            Stripe::setApiKey($this->stripeKey);
            $session = Session::retrieve($sessionId);
            if ($session->payment_status === 'paid') {
                $order->setPaymentIntent($session->payment_intent);
                $order->setState(1);
                $this->entityManager->flush();
                return true;
            }
            return false;
        } catch (ApiErrorException $e) {
            throw new StripeApiException($e->getMessage());
        }
    }

    /**
     * Restock the Product linked to each ProductSold in the Shop, if fail throws an Exception
     *
     * @param Order $order The current Order
     * @throws OrderHasNoProductException If the order has no ProductsSold
     */
    public function cancelOrder(Order $order)
    {
        if (!$order->getProductsSold()->isEmpty()) {
            foreach ($order->getProductsSold() as $item) {
                $item->getProduct()->setStock($item->getProduct()->getStock() + $item->getQuantity());
            }
            $this->entityManager->flush();
        } else {
            throw new OrderHasNoProductException();
        }
    }

    /**
     * Cancels the Order and resets it before it has persisted
     *
     * @param Order $order The current Order to cancel
     */
    private function cancelOrderBeforePersist(Order $order, array $productsSold)
    {
        $order
            ->setState(null)
            ->setItemsTotal(null)
            ->setLocaleCode(null)
            ->setShipment(null)
            ->setToken(null)
            ->setTotal(null)
            ->setItemsTotal(null)
            ->setTotalWithShipment(null)
            ->setNumber(null)
        ;
        $this->cancelProductsSold($order, $productsSold);
    }

    /**
     * Generates a token for the current Order
     *
     * @return string The token
     * @throws Exception If the system can't gather sufficient entropy
     */
    private function generateOrderToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
     * Creates an OrderNumber for the Order and stores it in the database so we can
     * increment the OrderNumber and create a unique number for each Order
     *
     * @return int The generated Order Number that is stored in the database
     */
    private function generateOrderNumber(): int
    {
        $lastNumbers = $this->numberRepository->findLast();
        $number = new OrderNumber();
        if (empty($lastNumbers)) {
            $orderNumber = 1;
            $number->setNumber($orderNumber);
        } else {
            foreach ($lastNumbers as $lastNumber) {
                $number->setNumber($lastNumber->getNumber() + 1);
            }
        }
        $this->entityManager->persist($number);
        $this->entityManager->flush();
        return $number->getNumber();
    }

    /**
     * Clears the ProductsSold
     *
     * @param Order $order The current Order
     */
    private function clearProductsSold(Order $order)
    {
        $order->getProductsSold()->clear();
    }

    /**
     * Cancels all the productsSold and refuel the Stock of the products
     *
     * @param Order $order The current Order to clear ProductsSold from
     * @param array $productsSold The array of the productsSold with Product stock already persisted
     */
    private function cancelProductsSold(Order $order, array $productsSold)
    {
        foreach ($productsSold as $productSold) {
            $productSold->getProduct()->setStock($productSold->getProduct()->getStock() + $productSold->getQuantity());
        }
        $this->clearProductsSold($order);
        $this->entityManager->flush();
    }
}