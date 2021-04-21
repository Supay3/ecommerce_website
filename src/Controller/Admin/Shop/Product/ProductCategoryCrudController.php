<?php

namespace App\Controller\Admin\Shop\Product;

use App\Entity\Shop\Product\ProductCategory;
use App\Form\Admin\Shop\Product\ProductCategoryImageType;
use App\Form\Admin\Shop\Product\ProductCategoryTranslationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code'),
            CollectionField::new('productCategoryTranslations', 'Traductions')
                ->onlyOnForms()
                ->setEntryType(ProductCategoryTranslationType::class),
            CollectionField::new('productCategoryTranslations', 'Traductions')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/_translation.html.twig'),
            CollectionField::new('images')
                ->onlyOnForms()
                ->setEntryType(ProductCategoryImageType::class),
            CollectionField::new('images')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/product/product_category_image.html.twig'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, 'detail');
    }
}
