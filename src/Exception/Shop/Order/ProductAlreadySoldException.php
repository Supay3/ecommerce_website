<?php


namespace App\Exception\Shop\Order;


use Exception;

class ProductAlreadySoldException extends Exception
{
    public const ERROR_NAME = 'order_error';

    public function errorMessage(): string
    {
        return 'Votre commande n\'a pas pu aboutir car le produit "' .  $this->getMessage() . '" que vous vouliez n\'est plus disponible ou bien n\'est pas disponible en quantité suffisante.';
    }
}