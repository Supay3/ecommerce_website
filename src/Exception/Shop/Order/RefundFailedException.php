<?php


namespace App\Exception\Shop\Order;


use Exception;

class RefundFailedException extends Exception
{
    public const ERROR_NAME = 'refund_error';

    public function errorMessage(): string
    {
        return 'Une erreur est survenue lors de votre demande de remboursement, soyez assuré(e) de notre empressement pour la résoudre.';
    }
}