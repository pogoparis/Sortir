<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class SiteController extends AbstractController
{
    #[Route('/site', name: 'site_gerer')]
    public function gerer(
SiteRepository $siteRepository
    ): Response
    {
        $sites = $siteRepository->findAll();
        return $this->render('site/index.html.twig', compact('sites'));
    }

    #[Route('/site/modifier', name: 'site_modif')]
    public function modif(
        SiteRepository $siteRepository
    ): Response
    {
        $sites = $siteRepository->findAll();
        return $this->render('site/index.html.twig', compact('sites'));
    }
}
