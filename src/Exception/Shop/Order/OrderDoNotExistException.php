<?php


namespace App\Exception\Shop\Order;


use Exception;

class OrderDoNotExistException extends Exception
{
    public const ERROR_NAME = 'order_error';

    public function errorMessage(): string
    {
        return 'Votre commande est inexistante';
    }
}