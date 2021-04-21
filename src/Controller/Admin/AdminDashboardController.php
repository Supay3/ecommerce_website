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
        yield MenuItem::subMenu('Magasin')->setSubItems([
            MenuItem::section('Locale'),
            MenuItem::linkToCrud('Locales', 'fas fa-language', Locale::class),

            MenuItem::section('Commandes'),
            MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Order::class),
            MenuItem::linkToCrud('Produits vendus', 'fas fa-dolly', ProductSold::class),

            MenuItem::section('Produit'),
            MenuItem::linkToCrud('Catégories de produit', 'fas fa-boxes', ProductCategory::class),
            MenuItem::linkToCrud('Types de produits', 'fas fa-tag', ProductType::class),
            MenuItem::linkToCrud('Marques de produits', 'far fa-copyright', ProductBrand::class),
            MenuItem::linkToCrud('Produits', 'fas fa-box', Product::class),

            MenuItem::section('Méthode de paiement'),
            MenuItem::linkToCrud('Méthodes de paiement', 'far fa-credit-card', PayementMethod::class),

            MenuItem::section('Livraison'),
            MenuItem::linkToCrud('Livraisons', 'fas fa-shipping-fast', Shipment::class),
        ]);
    }
}
