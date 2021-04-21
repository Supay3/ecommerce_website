<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute]
class UniqueLocale extends Constraint
{
    public string $message = 'The locale "{{ locale }}" is already used in an other translation';
}