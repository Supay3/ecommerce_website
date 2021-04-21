<?php

namespace App\Controller\Admin\Shop\Product;

use App\Entity\Shop\Product\ProductType;
use App\Form\Admin\Shop\Product\ProductTypeImageType;
use App\Form\Admin\Shop\Product\ProductTypeTranslationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Type')
            ->setEntityLabelInPlural('Types')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code'),
            AssociationField::new('productCategory'),
            CollectionField::new('productTypeTranslations', 'Traductions')
                ->onlyOnForms()
                ->setEntryType(ProductTypeTranslationType::class),
            CollectionField::new('productTypeTranslations', 'Traductions')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/_translation.html.twig'),
            CollectionField::new('images')
                ->onlyOnForms()
                ->setEntryType(ProductTypeImageType::class),
            CollectionField::new('images')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/product/product_type_image.html.twig'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, 'detail');
    }
}
