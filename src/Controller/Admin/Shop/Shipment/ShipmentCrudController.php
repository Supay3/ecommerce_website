<?php

namespace App\Controller\Admin\Shop\Shipment;

use App\Entity\Shop\Shipment\Shipment;
use App\Form\Admin\Shop\Shipment\ShipmentTranslationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ShipmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Shipment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Livraison')
            ->setEntityLabelInPlural('Livraisons')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code'),
            MoneyField::new('price', 'Prix')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),
            CollectionField::new('shipmentTranslations', 'Traductions')
                ->onlyOnForms()
                ->setEntryType(ShipmentTranslationType::class),
            CollectionField::new('shipmentTranslations', 'Traductions')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/_translation.html.twig'),
        ];
    }
}
