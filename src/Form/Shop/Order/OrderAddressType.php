<?php

namespace App\Form\Shop\Order;

use App\Entity\Shop\Order\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new Email(),
                    new NotBlank(),
                ],
            ])
            ->add('shippingAddress', AddressType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'shipping-form',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('notes', TextareaType::class, [
                'required' => false,
            ])
            ->add('billingAddress', AddressType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'billing-form',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'attr' => [
                'class' => 'mb-4',
            ]
        ]);
    }
}
