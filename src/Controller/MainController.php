<?php

namespace App\Controller;

use App\Entity\Filtre;
use App\Form\FiltreFormType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_index')]
    public function index(
        Request $request,
        SortieRepository $sortieRepository
    ): Response
    {
        $filtre = new Filtre();
        $form = $this->createForm(FiltreFormType::class, $filtre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sorties = $sortieRepository->findSearch($filtre);
        } else {
            $sorties = $sortieRepository->findAll();
        }
        return $this->render('main/index.html.twig',
            [
                'sorties' => $sorties,
                'form' => $form->createView(),
            ]);

    }
}
