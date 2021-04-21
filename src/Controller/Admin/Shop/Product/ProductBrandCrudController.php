<?php

namespace App\Controller\Admin\Shop\Product;

use App\Entity\Shop\Product\ProductBrand;
use App\Form\Admin\Shop\Product\ProductBrandImageType;
use App\Form\Admin\Shop\Product\ProductBrandTranslationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductBrandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductBrand::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Marque')
            ->setEntityLabelInPlural('Marques')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code'),
            TextField::new('link', 'Lien externe'),
            CollectionField::new('productBrandTranslations', 'Traductions')
                ->onlyOnForms()
                ->setEntryType(ProductBrandTranslationType::class),
            CollectionField::new('productBrandTranslations', 'Traductions')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/_translation.html.twig'),
            CollectionField::new('images')
                ->onlyOnForms()
                ->setEntryType(ProductBrandImageType::class),
            CollectionField::new('images')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/product/product_brand_image.html.twig'),
        ];
    }
}
