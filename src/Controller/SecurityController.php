<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
{
    $form = $this->createForm(LoginFormType::class);

    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('login/login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
        'loginForm' => $form->createView(),
    ]);
}

}
