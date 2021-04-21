<?php

namespace App\Form\Shop;

use App\Entity\Shop\Product\ProductCategory;
use App\Entity\Shop\Product\ProductType;
use App\Entity\Shop\ProductSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchType extends AbstractType
{

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrice', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix max',
                ],
            ])
            ->add('productTypes', EntityType::class, [
                'class' => ProductType::class,
                'choice_label' => function ($choice) {
                    $currentTranslation = 'Not defined';
                    foreach ($choice->getProductTypeTranslations() as $translation) {
                        if ($translation->getLocale()->getCode() === $this->requestStack->getCurrentRequest()->getLocale()) {
                            $currentTranslation = $translation->getName();
                        }
                    }
                    return $currentTranslation;
                },
                'multiple' => true,
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Par type',
                ],
            ])
            ->add('productCategories', EntityType::class, [
                'class' => ProductCategory::class,
                'choice_label' => function ($choice) {
                    $currentTranslation = 'Not defined';
                    foreach ($choice->getProductCategoryTranslations() as $translation) {
                        if ($translation->getLocale()->getCode() === $this->requestStack->getCurrentRequest()->getLocale()) {
                            $currentTranslation = $translation->getName();
                        }
                    }
                    return $currentTranslation;
                },
                'multiple' => true,
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Par Catégorie',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductSearch::class,
            'method' => 'GET',
            'label' => false,
        ]);
    }
}
