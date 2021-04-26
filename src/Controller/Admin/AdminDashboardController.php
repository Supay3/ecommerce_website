<?php

namespace App\Controller\Admin;

use App\Controller\RouteName;
use App\Entity\Shop\Locale;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\Order\ProductSold;
use App\Entity\Shop\Payement\PayementMethod;
use App\Entity\Shop\Product\Product;
use App\Entity\Shop\Product\ProductBrand;
use App\Entity\Shop\Product\ProductCategory;
use App\Entity\Shop\Product\ProductType;
use App\Entity\Shop\Shipment\Shipment;
use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: RouteName::ADMIN_INDEX)]
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mon magasin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Locale');
        yield MenuItem::linkToCrud('Locales', 'fas fa-language', Locale::class);

        yield MenuItem::section('Commandes');
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Produits vendus', 'fas fa-dolly', ProductSold::class);

        yield MenuItem::section('Produit');
        yield MenuItem::linkToCrud('Catégories de produit', 'fas fa-boxes', ProductCategory::class);
        yield MenuItem::linkToCrud('Types de produits', 'fas fa-tag', ProductType::class);
        yield MenuItem::linkToCrud('Marques de produits', 'far fa-copyright', ProductBrand::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-box', Product::class);

        yield MenuItem::section('Méthode de paiement');
        yield MenuItem::linkToCrud('Méthodes de paiement', 'far fa-credit-card', PayementMethod::class);

        yield MenuItem::section('Livraison');
        yield MenuItem::linkToCrud('Livraisons', 'fas fa-shipping-fast', Shipment::class);

        yield MenuItem::section('Utilisateur');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
    }
}
