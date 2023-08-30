<?php

namespace App\Controller;

use App\Entity\Filtre;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\FiltreFormType;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Repository\VilleLocalisationRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use DateTime;
use Symfony\Component\Validator\Constraints\Date;
use function Symfony\Component\Clock\now;

class SortieController extends AbstractController
{
//    ****************************** CREATION *****************************
    #[Route('/creation', name: 'sortie_creation')]
    public function creation(
        Request $requete, EtatRepository $etatRepository, SiteRepository $siteRepository, EntityManagerInterface $entityManager
    ): Response
    {
        //Nouvelle Sortie / Lieu / Ville
        $now = new DateTime();

        $lieu = new Lieu();
        $sortie = new Sortie();
        $ville = new Ville();
        //Etat de la sortie -> "créée" par défaut
        $etat = $etatRepository->findOneBy(array('id' => 1));
        $sortie->setEtat($etat);
        // Organisateur
        $sortie->setOrganisateur($this->getUser());
        // set siteEni
        $siteId = $this->getUser()->getSiteEni();
        $sortie->setDateHeureDebut($now);
        $sortie->setDateHeureFin($now);
        $sortie->setDateLimiteInscription($now);
        $siteUser = $siteRepository->findOneBy(array('id' => $siteId));
        $sortie->setSite($siteUser);
        // création du formulaire
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $villeForm = $this->createForm(VilleType::class, $ville);
        // récupération des données
        $sortieForm->handleRequest($requete);
        $lieuForm->handleRequest($requete);
        $villeForm->handleRequest($requete);

        if ($lieuForm->isSubmitted() && $lieuForm->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();
        }
        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();
        }
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $test = $requete->query->get('sortie[lieu]');
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Sortie créée , Publiez la si les informations sont correctes');
            return $this->redirectToRoute('sortie_detail', ['id' => $sortie->getId()]);
        }
        return $this->render('sortie/index.html.twig', compact("sortieForm", 'lieuForm', 'villeForm'));
    }

//    ********************************* LISTE SORTIES ****************************************
    #[Route('/sorties', name: 'sortie_affichage')]
    public function affichage(
        SortieRepository $sortieRepository,
        UserRepository   $userRepository,
        Request          $request
    ): Response
    {
        $filtre = new Filtre();
        $now = new DateTime();
        $user = $userRepository->findOneBy(array('id' => $this->getUser()->getId()));
        $form = $this->createForm(FiltreFormType::class, $filtre);
        $form->handleRequest($request);
        $compteurParticipants = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $sorties = $sortieRepository->findSearch($filtre);
        } else {
            $sorties = $sortieRepository->findAllPerso();
        }
        foreach ($sorties as $sortie) {
            $nbParticipants = $sortieRepository->countParticipants($sortie);
            $compteurParticipants[$sortie->getId()] = $nbParticipants;

        }
        return $this->render('sortie/listeSorties.html.twig',
            [
                'sorties' => $sorties,
                'nbParticipants' => $compteurParticipants,
                'form' => $form->createView(),
                'now' => $now,
                'user' => $user
            ]);
    }

//*********************************** DETAILS **************************************
    #[Route('/detail/{id}', name: 'sortie_detail', requirements: ["id" => "\d+"])]
    public function detail(
        Sortie         $sortie,
        UserRepository $userRepository
    ): Response
    {
        $now = new DateTime();
        $user = $userRepository->findOneBy(array('id' => $this->getUser()->getId()));
        return $this->render('sortie/detail.html.twig', compact('sortie', 'now', 'user'));
    }

    // ************************************* ANNULER ******************************************
    #[Route('/annuler/{id}', name: 'sortie_annuler', requirements: ["id" => "\d+"])]
    public function annuler(Sortie $sortie, EtatRepository $etatRepository, EntityManagerInterface $entityManager): Response
    {
        $etat = $etatRepository->findOneBy(array('id' => 6));
        $sortie->setEtat($etat);
        $entityManager->persist($sortie);
        $entityManager->flush();
        return $this->redirectToRoute('sortie_affichage');
    }

    // *********************************** MODIFICATION *************************************************
    #[Route('/modifier/{id}', name: 'sortie_modifier', requirements: ["id" => "\d+"])]
    public function modifier(Sortie $sortie, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('sortie_affichage');
        }
        return $this->render('sortie/modifier.html.twig', compact('sortieForm'));
    }
    // *********************************** API *********************************************************
    #[Route('/api', name: 'api_villes')]
    public function apiVille(
        VilleRepository $villeRepository, SerializerInterface $serializer): Response
    {
        $villes = $villeRepository->findAll();

        return new JsonResponse(
            $serializer->serialize(
                $villes,
                'json',
                ['groups' => 'sorties:ville']),
            200,
            [],
            true);
    }

    #[Route('/apiLieux/{id}', name: 'api_lieux', requirements: ["id" => "\d+"])]
    public function apiLieux(
        LieuRepository $lieuRepository, SerializerInterface $serializer, string $id): Response
    {
        $lieu = $lieuRepository->findBy(array('ville' => $id));
        return new JsonResponse(
            $serializer->serialize(
                $lieu,
                'json',
                ['groups' => 'sorties:lieux']),
            200,
            [],
            true);
    }

    #[Route('/apiLocalisation', name: 'api_Localisation')]
    public function apiVilleLocalisation(
        VilleLocalisationRepository $villeLocalisationRepository, SerializerInterface $serializer): Response
    {
        $villes = $villeLocalisationRepository->findAll();
        return new JsonResponse(
            $serializer->serialize(
                $villes,
                'json',
                ['groups' => 'local:read']),
            200,
            [],
            true);
    }
// *********************************** LIEU *********************************************************
    #[Route('/creationLocalisation/{latitude}/{longitude}', name: 'sortie_creationLocalisation', requirements: ["latitude" => "-?\d+\.\d+", "longitude" => "-?\d+\.\d+"])]
    public function creationLieu(VilleRepository $villeRepository, EntityManagerInterface $entityManager, Request $request, string $latitude, string $longitude): Response
    {
        $lieu = new Lieu();
        $ville = $villeRepository->findOneBy(array('id' => 1));
//        $ville =  $villeRepository->findAll();
        $latitudeInt = (float)$latitude;
        $longitudeInt = (float)$longitude;
        $lieu->setLatitude($latitudeInt);
        $lieu->setLongitude($longitudeInt);
        $lieu->setVille($ville);
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $lieuForm->handleRequest($request);

        if ($lieuForm->isSubmitted() && $lieuForm->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();
            return $this->redirectToRoute('sortie_affichage');
        }
        return $this->render('sortie/creationLocalisation.html.twig', compact('lieuForm', 'ville'));
    }

    #[Route('/creationLieuVide', name: 'sortie_creationlieuVide', methods: ['POST'])]
    public function creationLieuVide(Request $request, SerializerInterface $serializer, VilleRepository $villeRepository, EntityManagerInterface $entityManager): Response
    {
        $req = $request->toArray();
        $lieu = (new Lieu())
            ->setNom($req['nom'])
            ->setRue($req['rue'])
            ->setLatitude($req['latitude'])
            ->setLongitude($req['longitude'])
            ->setVille($villeRepository->find($req["ville"]));
        $entityManager->persist($lieu);
        $entityManager->flush();

        return $this->json(
            $villeRepository->findBy([], ['nom' => 'ASC']),
            201,
            [],
            ['groups' => 'listeLieux']
        );
    }
    #[Route('/creationVilleVide', name: 'sortie_creationVilleVide', methods: ['POST'])]
    public function creationVilleVide(Request $request, SerializerInterface $serializer, VilleRepository $villeRepository, EntityManagerInterface $entityManager): Response
    {
        $req = $request->toArray();
        $ville = (new Ville())
            ->setNom($req['nom'])
            ->setCodePostal($req['codePostal'])
            ->setLatitude($req['latitude'])
            ->setLongitude($req['longitude']);
        $entityManager->persist($ville);
        $entityManager->flush();

        $allVille = $villeRepository->findAll();

        return new JsonResponse(
            $serializer->serialize(
                $allVille,
                'json',
                ['groups' => 'sorties:ville']),
            200,
            [],
            true);
    }
}
