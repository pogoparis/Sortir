<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

//#[Route('/info',name:'/profil')]
class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil_detail')]
    public function detail(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(array('id' => $this->getUser()->getId()));

        $form = $this->createForm(ProfilFormType::class, $user);
        $form->handleRequest($request);
        //$userEntity=$userRepository->findOneBy(array ('id'=>$user));
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour');
           return $this->redirectToRoute('main_index');
        }
        return $this->render('profil/detail.html.twig', [
            'profilForm' => $form->createView(),
            //'user'=> $userEntity
        ]);
    }

    #[Route('/profil/{id}', name: 'profil_participant_detail',requirements: ["user"=>"\d+"])]
    public function participant_detail(
        int $id,
        UserRepository $userRepository
    ):Response
    {
        $user= $userRepository->findOneBy(["id"=>$id]);
        return $this->render('profil/participant_detail.html.twig',
        compact("user"));

    }

}