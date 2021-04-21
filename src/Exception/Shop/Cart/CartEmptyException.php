<?php


namespace App\Exception\Shop\Cart;


use Exception;

class CartEmptyException extends Exception
{
    public const ERROR_NAME = 'cart_error';

    public function errorMessage(): string
    {
        return "Votre panier est vide.";
    }
}