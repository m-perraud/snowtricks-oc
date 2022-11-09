<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(): Response
    {
        return $this->render('home/register.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('home/login.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/forgotpw', name: 'forgot_pw')]
    public function forgotPw(): Response
    {
        return $this->render('home/forgotpw.html.twig', [
            'controller_name' => 'HomeController',
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
}
