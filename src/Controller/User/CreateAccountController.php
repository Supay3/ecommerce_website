<?php


namespace App\Controller\User;


use App\Controller\RouteName;
use App\Entity\User\User;
use App\Form\User\CreateAccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// TODO: refactor
class CreateAccountController extends AbstractController
{
    #[Route('/{_locale}/create-account', name: RouteName::USER_CREATE_ACCOUNT)]
    public function createAccount(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(CreateAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $entityManager->persist($user);
            $entityManager->flush();
            // TODO : s'occuper de ce message dans les views
            $this->addFlash('success', 'Votre compte a bien été créé, merci de vérifier votre adresse email');
            return $this->redirectToRoute(RouteName::SHOP_INDEX);
        }

        return $this->render('user/create_account.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}