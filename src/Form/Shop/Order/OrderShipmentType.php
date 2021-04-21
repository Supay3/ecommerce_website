<?php

namespace App\Form\Shop\Order;

use App\Entity\Shop\Order\Order;
use App\Entity\Shop\Shipment\Shipment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderShipmentType extends AbstractType
{

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shipment', EntityType::class, [
                'class' => Shipment::class,
                'choice_label' => function ($choice) {
                    $currentTranslation = 'Not defined';
                    foreach ($choice->getShipmentTranslations() as $translation) {
                        if ($translation->getLocale()->getCode() === $this->requestStack->getCurrentRequest()->getLocale()) {
                            $currentTranslation = $translation->getName();
                        }
                    }
                    return $currentTranslation . ' ' . number_format($choice->getPrice(), 2) . '€';
                },
                'label' => 'Mode de livraison',
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
