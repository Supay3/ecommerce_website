<?php


namespace App\Exception\Shop\Order\Notification;


use Exception;

class OrderNotificationFailedException extends Exception
{
    public const ERROR_NAME = 'order_notification_error';

    public function errorMessage(): string
    {
        return 'Une erreur est survenue lors de l\'envoi du mail, êtes-vous sûrs que ce mail est le bon ?';
    }
}