<?php


namespace App\Exception\Shop\Order\User;


class UserHasNoAddressException extends \Exception
{
    public const ERROR_NAME = 'no_address_error';

    public function errorMessage(): string
    {
        return 'Vous n\'avez pas donné d\'adresse';
    }
}