<?php


namespace App\Controller\User;


use App\Controller\RouteName;
use App\Entity\User\User;
use App\Form\User\CreateAccountType;
use App\Repository\Shop\LocaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// TODO: refactor
class CreateAccountController extends AbstractController
{
    private LocaleRepository $localeRepository;

    public function __construct(LocaleRepository $localeRepository)
    {
        $this->localeRepository = $localeRepository;
    }

    /**
     * @IsGranted("IS_ANONYMOUS")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    #[Route('/{_locale}/create-account', name: RouteName::USER_CREATE_ACCOUNT)]
    public function createAccount(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response
    {
        $locale = $request->getLocale();
        if (!RouteName::checkAuthorizedLocales($this->localeRepository->findAll(), $locale)) {
            throw new NotFoundHttpException();
        }
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