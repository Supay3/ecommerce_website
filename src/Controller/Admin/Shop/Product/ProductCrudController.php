<?php

namespace App\Controller\Admin\Shop\Product;

use App\Entity\Shop\Product\Product;
use App\Form\Admin\Shop\Product\ProductImageType;
use App\Form\Admin\Shop\Product\ProductTranslationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code'),
            TextField::new('reference', 'Référence'),
            MoneyField::new('price', 'Prix')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),
            IntegerField::new('promotion')
                ->setHelp('En %'),
            IntegerField::new('stock'),
            NumberField::new('width', 'Largeur')
                ->setHelp('En centimètres'),
            NumberField::new('height', 'Hauteur')
                ->setHelp('En centimètres'),
            NumberField::new('depth', 'Profondeur')
                ->setHelp('En centimètres'),
            NumberField::new('weight', 'Poids')
                ->setHelp('En grammes'),
            BooleanField::new('enabled', 'Activé'),
            AssociationField::new('productType', 'Type du produit'),
            AssociationField::new('productBrand', 'Marque'),
            CollectionField::new('productTranslations', 'Traductions')
                ->onlyOnForms()
                ->setEntryType(ProductTranslationType::class),
            CollectionField::new('productTranslations', 'Traductions')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/_translation.html.twig'),
            CollectionField::new('images')
                ->onlyOnForms()
                ->setEntryType(ProductImageType::class),
            CollectionField::new('images')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/product/product_image.html.twig'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, 'detail');
    }
}
