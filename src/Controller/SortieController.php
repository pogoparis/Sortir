<?php

namespace App\Controller;

use App\Entity\Filtre;
use App\Entity\Sortie;
use App\Form\FiltreFormType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use DateTime;

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
//        $lieu = $lieuRepository->findOneBy(array('id'=> 5));
//        $sortie->setLieu($lieu);

        // création du formulaire
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($requete);



         if ($sortieForm -> isSubmitted() ){
             $test = $requete->query->get('sortie[lieu]');

            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('main_index');
        }
        return $this->render('sortie/index.html.twig', compact("sortieForm"));
    }
    #[Route('/sorties', name: 'sortie_affichage')]
    public function affichage(SortieRepository $sortieRepository, Request $request
    ): Response
    {
        $filtre = new Filtre();
        $form = $this->createForm(FiltreFormType::class, $filtre);
        $form->handleRequest($request);
        $sorties = $sortieRepository->findSearch($filtre);


        return $this->render('sortie/listeSorties.html.twig',
            [
                'sorties' => $sorties,
                'form' => $form,
            ]);
    }

    #[Route('/detail/{id}', name: 'sortie_detail', requirements: ["id" => "\d+"])]
    public function detail(Sortie $sortie): Response
    {
        $now = new DateTime();
        $minNow = date_timestamp_get($now);

        $limite = $sortie->getDateLimiteInscription();
        $minLimite = date_timestamp_get($limite);
        $difference = ($minLimite - $minNow);
        return $this->render('sortie/detail.html.twig', compact('sortie', 'difference'));
    }
    #[Route('/annuler/{id}', name: 'sortie_annuler', requirements: ["id" => "\d+"])]
public function annuler(Sortie $sortie, EtatRepository $etatRepository, EntityManagerInterface $entityManager): Response
{
    $etat = $etatRepository->findOneBy(array('id'=> 6));
    $sortie->setEtat($etat);
    $entityManager->persist($sortie);
    $entityManager->flush();
    return $this->redirectToRoute('sortie_affichage');
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

    #[Route('/api', name: 'api_villes')]
    public function apiVille(
        VilleRepository $villeRepository, SerializerInterface $serializer): Response
    {
        $villes = $villeRepository->findAll();

        return new JsonResponse(
            $serializer->serialize(
                $villes,
                'json',
                ['groups'=>'wishes:read']),
            200,
            [],
            true);
    }
    #[Route('/apiLieux/{id}', name: 'api_lieux', requirements: ["id" => "\d+"])]
    public function apiLieux(
        LieuRepository $lieuRepository, SerializerInterface $serializer, string $id): Response
    {
        $lieu = $lieuRepository->findBy(array('ville'=> $id));
        return new JsonResponse(
            $serializer->serialize(
                $lieu,
                'json',
                ['groups'=>'wishes:read']),
            200,
            [],
            true);
    }
}
