<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{

    #[Route('/figure/{slug}', name: 'figure_details')]
    public function figureDetails(FigureRepository $repo, string $slug): Response
    {
    $figures = $repo->findOneBy(['slug' => $slug]);
    //$comments = $doctrine->getRepository(Comment::class)->findOneBy(['slug' => $slug]);

    return $this->render('figure/figuredetails.html.twig', [
        'controller_name' => 'FigureController',
        'figures' => $figures
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
    public function figureNew(Request $request): Response
    {
        
        $figure = new Figure();

        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setCreatedAt(new \DateTimeImmutable());
            $figure = $form->getData();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('figure/figurenew.html.twig', [
            'controller_name' => 'FigureController',
            'formFigure' => $form->createView()
        ]);
    }


}
