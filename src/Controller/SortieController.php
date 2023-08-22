<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/creation', name: 'sortie_creation')]
    public function creation(
        Request $requete, EtatRepository $etatRepository, LieuRepository $lieuRepository, SiteRepository $siteRepository, EntityManagerInterface $entityManager
    ): Response
    {

        //Nouvelle Sortie

        $sortie = new Sortie();
        //Etat de la sortie -> "créée" par défaut
        $etat = $etatRepository->findOneBy(array('id' => 4));
        $sortie->setEtat($etat);

        $sortie->setOrganisateur($this->getUser());

        // Organisateur
        $sortie->setOrganisateur($this->getUser());
        // set siteEni
        $siteId = $this->getUser()->getSiteEni();
        $siteUser = $siteRepository->findOneBy(array('id' => $siteId ));
        $sortie->setSite($siteUser);


        //TODO Mettre le lieu en formulaire
        //Lieu en dur
        $lieu = $lieuRepository->findOneBy(array('id'=> 5));
        $sortie->setLieu($lieu);

        // création du formulaire
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($requete);

        if ($sortieForm -> isSubmitted() && $sortieForm->isValid()){
            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('main_index');
        }
        return $this->render('sortie/index.html.twig', compact("sortieForm"));
    }
    #[Route('/sorties', name: 'sortie_affichage')]
    public function affichage(SortieRepository $sortieRepository): Response
    {
        $sorties = $sortieRepository->findAll();
        return $this->render('sortie/listeSorties.html.twig', compact('sorties'));
    }

    #[Route('/detail/{id}', name: 'sortie_detail', requirements: ["id" => "\d+"])]
    public function detail(Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/detail.html.twig', compact('sortie'));
    }
    #[Route('/modifier/{id}', name: 'sortie_modifier', requirements: ["id" => "\d+"])]
    public function modifier(Sortie $sortie, SortieRepository $sortieRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm -> isSubmitted() && $sortieForm->isValid()){
            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('sortie_affichage');
        }
        return $this->render('sortie/modifier.html.twig', compact('sortieForm'));
    }
}
