<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user ->setIsAdmin(false);//permet de config par défaut le isAdmin a false
        $user->setIsActif(true);//permet de config par défaut le isActif a false
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Inscription bien prise en compte :)!! Confirmez votre adresse mail et connectez vous ^^!! ');

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('admin@sortir.org', 'Administrateur Sortir'))
                    ->to($user->getEmail())
                    ->subject('Veillez confirmer votre adresse mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );


            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
            if($this->getUser()->isVerified()){
                return $this->redirectToRoute('app_login');
            }else{
                $this->addFlash('Vérification du mail en erreur', 'Vous devez vérifier votre email avant de vous connecter.');
                return $this->redirectToRoute('app_login');
            }

        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('Vérification du mail en erreur', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre mail a bien été validé.');

        return $this->redirectToRoute('app_login');
    }

/*    #[Route(path: '/verify/email', name: 'app_verify_email')]
    public function verifyEmail(Request $request, VerifyEmailHelperInterface $verifyEmailHelper, UserRepository $userRepository, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $user1 = $userRepository->find($request->query->get('id'));
        if (!$user1) {
            throw $this->createNotFoundException();
        }
        try {
            $verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                $user->getId(),
                $user->getEmail(),
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', $e->getReason());
            return $this->redirectToRoute('app_register');
        }
        $user->setIsVerified(true);
        $entityManager->flush($user);

        $this->addFlash('success', 'Compte vérifié ! Vous pouvez maintenant vous connecter.');
        return $this->redirectToRoute('app_login');
    }*/
}
