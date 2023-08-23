<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipationController extends AbstractController
{
    #[Route('/inscription', name: 'participation_inscription')]
    public function index(SortieRepository $sortieRepository): Response
    {

        $participants = new Sortie();
        $participant = $this->getUser();

        return $this->render('participation/index.html.twig');
    }
}
