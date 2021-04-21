<?php


namespace App\Notification\Shop\Order;


use App\Entity\Shop\Order\Order;
use App\Exception\Shop\Order\Notification\OrderNotificationFailedException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class OrderNotification
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param Order $order
     * @throws OrderNotificationFailedException
     */
    public function notifyOrderValidation(Order $order)
    {
        try {
            $email = (new TemplatedEmail())
                ->from('no-reply@dubout.fr')
                ->to($order->getEmail())
                ->subject('Confirmation de commande n°' . $order->getNumber())
                ->htmlTemplate('emails/order_validation.html.twig')
                ->context([
                    'order' => $order,
                    'orderState' => Order::STATE,
                ])
            ;
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new OrderNotificationFailedException($e->getMessage());
        }
    }

    /**
     * @param Order $order
     * @throws OrderNotificationFailedException
     */
    public function notifyOrderPayment(Order $order)
    {
        try {
            $email = (new TemplatedEmail())
                ->from('no-reply@dubout.fr')
                ->to($order->getEmail())
                ->subject('Paiement bien reçu pour la commande n°' . $order->getNumber())
                ->htmlTemplate('emails/order_paid.html.twig')
                ->context([
                    'order' => $order,
                    'orderState' => Order::STATE,
                ])
            ;
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new OrderNotificationFailedException($e->getMessage());
        }
    }
}