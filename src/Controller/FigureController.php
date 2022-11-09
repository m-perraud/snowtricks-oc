<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    #[Route('/figure/:slug', name: 'figure_details')]
    public function figureDetails(): Response
    {
        return $this->render('figure/figuredetails.html.twig', [
            'controller_name' => 'FigureController',
        ]);
    }

    #[Route('/figuremod/:id', name: 'figure_mods')]
    public function figureMods(): Response
    {
        return $this->render('figure/figuremods.html.twig', [
            'controller_name' => 'FigureController',
        ]);
    }

    #[Route('/figurenew', name: 'figure_new')]
    public function figureNew(): Response
    {
        return $this->render('figure/figurenew.html.twig', [
            'controller_name' => 'FigureController',
        ]);
    }
}
