<?php


namespace App\Exception\Shop\Order;


use Exception;

class OrderHasNoProductException extends Exception
{
    public const ERROR_NAME = 'order_error';

    public function errorMessage(): string
    {
        return "Votre commande ne contient aucun produits et n'a donc pas aboutie.";
    }
}