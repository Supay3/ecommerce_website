<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Locale;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LocaleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Locale::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code'),
        ];
    }
}
