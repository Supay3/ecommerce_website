<?php


namespace App\Exception\Shop\Order;


use Exception;

class NoShipmentException extends Exception
{
    public const ERROR_NAME = 'order_error';

    public function errorMessage(): string
    {
        return 'Vous n\'avez pas sélectionné de mode de livraison';
    }

}