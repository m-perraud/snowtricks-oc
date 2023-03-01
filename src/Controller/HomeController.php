<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Repository\FigureRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine, Request $request, FigureRepository $repo): Response
    {
        $figures = $repo->findFiguresPaginated(1, 6);

        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'figures' => $figures 
        ]);
    }

    #[Route('/resetpw', name: 'reset_pw')]
    public function resetPw(): Response
    {
        return $this->render('home/resetpw.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/page404', name: 'page_404')]
    public function page404(): Response
    {
        return $this->render('home/page404.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/page403', name: 'page_403')]
    public function page403(): Response
    {
        return $this->render('home/page403.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/page500', name: 'page_500')]
    public function page500(): Response
    {
        return $this->render('home/page500.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/pagination', name: 'app_pagination')]
    public function pagination(Request $request, FigureRepository $repo): Response
    {
        $page = $request->query->getInt('page', 1);

        $figures = $repo->findFiguresPaginated($page, 6);

        return $this->render('home/pagination.html.twig', [
            'controller_name' => 'HomeController',
            'figures' => $figures 
        ]);
    }
} 
