<?php

namespace App\Controller\Admin\Shop\Payement;

use App\Entity\Shop\Payement\PayementMethod;
use App\Form\Admin\Shop\Payement\PayementMethodTranslationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PayementMethodCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PayementMethod::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Méthode de paiement')
            ->setEntityLabelInPlural('Méthodes de paiement')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code'),
            BooleanField::new('enabled', 'Activé'),
            CollectionField::new('payementMethodTranslations', 'Traductions')
                ->onlyOnForms()
                ->setEntryType(PayementMethodTranslationType::class),
            CollectionField::new('payementMethodTranslations', 'Traductions')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/_translation.html.twig'),
        ];
    }
}
