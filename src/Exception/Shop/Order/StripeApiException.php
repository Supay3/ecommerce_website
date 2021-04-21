<?php


namespace App\Exception\Shop\Order;


use Exception;

class StripeApiException extends Exception
{
    public const ERROR_NAME = 'api_error';

    public function errorMessage(): string
    {
        return 'Une erreur est survenue lors de la vérification de votre paiement, nous allons faire notre possible pour la résoudre';
    }
}