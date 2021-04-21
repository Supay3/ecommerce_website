<?php


namespace App\Validator;


use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueLocaleValidator extends ConstraintValidator
{

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueLocale) {
            throw new UnexpectedTypeException($constraint, UniqueLocale::class);
        }

        if (null === $value || '' === $value) {
            return;
        }
        if (!$value instanceof Collection) {
            throw new UnexpectedValueException($value, Collection::class);
        }
        $locales = [];
        foreach ($value as $item) {
            $localeCode = $item->getLocale()->getCode();
            if (in_array($localeCode, $locales)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ locale }}', $localeCode)
                    ->addViolation()
                ;
            }
            $locales[] = $localeCode;
        }
    }
}