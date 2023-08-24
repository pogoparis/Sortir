<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipationController extends AbstractController
{
    #[Route('/inscription/{id}', name: 'participation_inscription', requirements: ["id" => "\d+"])]
    public function index(Sortie $sortie, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $participant = $this->getUser();
        $idUser = $participant->getId();
        $userCo =  $userRepository->findOneBy(array('id'=> $idUser));
        $sortie->addParticipant($userCo);
        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('success', 'Votre participation a bien été prise en compte');
        return $this->redirectToRoute('sortie_affichage');
    }
    #[Route('/annulerParticipation/{id}', name: 'participation_annuler', requirements: ["id" => "\d+"])]
    public function annulerParticipation(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $participants = $sortie->getParticipants();
        foreach ($participants as $item){
            if($item === $user){
                $sortie->removeParticipant($user);
            }
        }

        $entityManager->persist($sortie);
        $entityManager->flush();
        return $this->redirectToRoute('sortie_affichage');
    }
}


