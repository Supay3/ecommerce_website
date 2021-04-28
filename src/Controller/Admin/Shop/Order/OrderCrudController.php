<?php

namespace App\Controller\Admin\Shop\Order;

use App\Entity\Shop\Order\Order;
use App\Exception\Shop\Order\OrderHasNoProductException;
use App\Exception\Shop\Order\RefundFailedException;
use App\Form\Admin\Shop\Order\ProductSoldType;
use App\Services\Shop\Order\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{

    /**
     * @var OrderService
     */
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {

        $this->orderService = $orderService;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            ->setDefaultSort(['state' => 'ASC', 'created_at' => 'DESC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('number', 'Numéro de commande')
                ->hideOnForm(),
            MoneyField::new('total')
                ->hideOnForm()
                ->setCurrency('EUR')
                ->setStoredAsCents(false),
            MoneyField::new('totalWithShipment', 'Total avec FDP')
                ->hideOnForm()
                ->setStoredAsCents(false)
                ->setCurrency('EUR'),
            IntegerField::new('itemsTotal', 'Nombre de produits')
                ->hideOnForm(),
            TextField::new('locale_code', 'Locale')
                ->hideOnForm()
                ->hideOnIndex(),
            ChoiceField::new('state', 'État de la commande')
                ->setChoices(array_flip(Order::STATE)),
            EmailField::new('email')
                ->hideOnForm(),
            TextareaField::new('notes')
                ->hideOnForm()
                ->hideOnIndex(),
            CollectionField::new('productsSold', 'Produits vendus')
                ->setTemplatePath('admin/shop/order/_products_sold.html.twig')
                ->onlyOnDetail(),
            DateField::new('created_at', 'Commandée le')
                ->setFormat('d/MM/Y à HH:mm:ss')
                ->hideOnForm(),
            AssociationField::new('user', 'Utilisateur')
                ->setTemplatePath('admin/shop/order/_user.html.twig')
                ->onlyOnDetail(),
            AssociationField::new('user', 'Utilisateur')
                ->onlyOnIndex(),
            AssociationField::new('shippingAddress', 'Adresse de livraison')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/order/_address.html.twig'),
            AssociationField::new('billingAddress', 'Adresse de facturation')
                ->onlyOnDetail()
                ->setTemplatePath('admin/shop/order/_address.html.twig'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
        ;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // If the Order is cancelled
        if ($entityInstance->getState() === 5) {
            try {
                $this->orderService->cancelOrder($entityInstance);
            } catch (OrderHasNoProductException $e) {
                $this->addFlash('error', $e->errorMessage());
                return;
            }
            parent::updateEntity($entityManager, $entityInstance);
            return;
        }
        // if the Order has already been paid
        if ($entityInstance->getState() === 6) {
            try {
                $this->orderService->manageRefundStripeApi($entityInstance);
                $this->addFlash('success', 'Le remboursement de la commande a été pris en compte');
            } catch (OrderHasNoProductException|RefundFailedException $e) {
                $this->addFlash($e::ERROR_NAME, $e->errorMessage());
                parent::updateEntity($entityManager, $entityInstance);
                return;
            }
        }
        parent::updateEntity($entityManager, $entityInstance);
    }
}
