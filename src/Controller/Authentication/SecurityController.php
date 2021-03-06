<?php

namespace App\Controller\Authentication;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

  /**
   * @Route("/", name="base")
   */
  public function baseUrl(): Response
  {
      return $this->redirectToRoute('login');;
  }

  /**
   * @Route("/login", name="login")
   */
  public function index(AuthenticationUtils $authenticationUtils): Response
  {

    // already logged in redirect to home
    if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED') ) {
      return $this->redirectToRoute('app_submissions');
    }

    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('security/login.html.twig', [
      'controller_name' => 'LoginController',
      'last_username' => $lastUsername,
      'error'         => $error,
    ]);
  }

  /**
   * @Route("/logout", name="app_logout", methods={"GET"})
   */
  public function logout(): void
  {
    // controller can be blank: it will never be called!
    throw new \Exception('Don\'t forget to activate logout in security.yaml');
  }
}
