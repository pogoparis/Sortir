<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, SessionInterface $session): Response
    {

        if ($this->getUser()) {
                return $this->redirectToRoute('sortie_affichage'); // Redirect back to the login page
            }

            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();
            $isVerifiedError=$session->get('isNotVerified');
            return $this->render('security/login.html.twig',
                [
                    'last_username' => $lastUsername,
                    'error' => $error,
                    'is_verified_error'=>$isVerifiedError
                ]);
        }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {

        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



     #[Route("/logout_message", name:"logout_message")]
     public function logoutMessage()
    {
        $this->addFlash('success', "Vous êtes bien déconnecté !");
        return $this->redirectToRoute('main_index');
    }
}