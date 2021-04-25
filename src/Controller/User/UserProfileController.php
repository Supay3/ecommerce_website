<?php


namespace App\Controller\User;


use App\Controller\RouteName;
use App\Entity\Shop\Order\Address;
use App\Entity\Shop\Order\Order;
use App\Form\User\Profile\UserAddressType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

// TODO : Faire un refactor de cette class
/**
 * @IsGranted("ROLE_USER")
 */
#[Route('/{_locale}/profile')]
class UserProfileController extends AbstractController
{

    #[Route('/', name: RouteName::USER_PROFILE_INDEX)]
    public function profileIndex(): Response
    {
        return $this->render('user/profile/index.html.twig');
    }

    #[Route('/add-address', name: RouteName::USER_PROFILE_ADD_ADDRESS)]
    public function addAddress(Request $request, EntityManagerInterface $entityManager): Response
    {
        $address = new Address();
        $form = $this->createForm(UserAddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address
                ->setUser($this->getUser())
                ->setPhoneNumber($this->getUser()->getPhoneNumber())
            ;
            $entityManager->persist($address);
            $entityManager->flush();
            return $this->redirectToRoute(RouteName::USER_PROFILE_INDEX);
        }
        return $this->render('user/profile/add_address.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit-address/{id}', name: RouteName::USER_PROFILE_EDIT_ADDRESS)]
    public function editAddress(Address $address, Request $request, EntityManagerInterface $entityManager): NotFoundHttpException|RedirectResponse|Response
    {
        if ($address->getUser() !== $this->getUser()) {
            return new NotFoundHttpException();
        }
        $form = $this->createForm(UserAddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute(RouteName::USER_PROFILE_INDEX);
        }
        return $this->render('user/profile/edit_address.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete-address/{id}', name: RouteName::USER_PROFILE_DELETE_ADDRESS)]
    public function deleteAddress(
        Address $address,
        Request $request,
        EntityManagerInterface $entityManager
    ): NotFoundHttpException|RedirectResponse
    {
        if ($address->getUser() !== $this->getUser()) {
            return new NotFoundHttpException();
        }
        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('_token'))) {
            $address->setUser(null);
            $entityManager->flush();
        }
        return $this->redirectToRoute(RouteName::USER_PROFILE_INDEX);
    }

    #[Route('/order-index', name: RouteName::USER_ORDER_INDEX)]
    public function orderIndex(): Response
    {
        return $this->render('user/order/index.html.twig', [
            'orderState' => Order::STATE,
        ]);
    }

    #[Route('/order-{id}-{number}', name: RouteName::USER_ORDER)]
    public function order(Order $order, $number): RedirectResponse|Response
    {
        if ($order->getUser() !== $this->getUser()) {
            throw new NotFoundHttpException();
        }
        if ($number !== $order->getNumber()) {
            return $this->redirectToRoute(RouteName::USER_ORDER, [
                'id' => $order->getId(),
                'number' => $number
            ]);
        }
        return $this->render('shop/order/order.html.twig', [
            'order' => $order,
            'orderState' => $order::STATE,
        ]);
    }
}