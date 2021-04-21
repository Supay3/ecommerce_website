<?php


namespace App\Exception\Shop\Cart;


use Exception;

class TooMuchInCartException extends Exception
{
    public const ERROR_NAME = 'too_much_cart_error';

    public function errorMessage(): string
    {
        return 'Vous ne pouvez pas ajouter ce produit car le stock est insuffisant';
    }

}