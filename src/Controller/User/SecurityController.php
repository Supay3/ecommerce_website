<?php

namespace App\Controller\User;

use App\Controller\RouteName;
use App\Repository\Shop\LocaleRepository;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/{_locale}')]
class SecurityController extends AbstractController
{
    private LocaleRepository $localeRepository;

    public function __construct(LocaleRepository $localeRepository)
    {
        $this->localeRepository = $localeRepository;
    }

    #[Route('/login', name: RouteName::APP_LOGIN)]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $locale = $request->getLocale();
        if (!RouteName::checkAuthorizedLocales($this->localeRepository->findAll(), $locale)) {
            throw new NotFoundHttpException();
        }
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/logout', name: RouteName::APP_LOGOUT)]
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
