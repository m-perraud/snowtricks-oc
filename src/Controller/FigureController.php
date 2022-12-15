<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Images;
use App\Entity\Videos;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use App\Repository\ImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{

    #[Route('/figure/{slug}', name: 'figure_details')]
    public function figureDetails(FigureRepository $repo, string $slug): Response
    {
    $figures = $repo->findOneBy(['slug' => $slug]);

    return $this->render('figure/figuredetails.html.twig', [
        'controller_name' => 'FigureController',
        'figures' => $figures
    ]);
}


    #[Route('/figurenew', name: 'figure_new')]
    #[Route('/figure/{id}/edit', name: 'figure_mods')]
    public function figureNew(Figure $figure = null, Request $request): Response
    {
        if(!$figure){
            $figure = new Figure();
        }
        $slugger = new AsciiSlugger();

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!$figure->getId()){
                $figure->setCreatedAt(new \DateTimeImmutable());
            } else {
                $figure->setUpdatedAt(new \DateTimeInterface());
            }

            $images = $form->get('images')->getData();



            // Il faudra vérifier si on a déjà une main ou pas. 
            $haveMainImage = false;

            foreach($images as $image){

                if(!$haveMainImage){
                    $image->setMainImage(true);
                    $haveMainImage= true;
                }

                $file = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                $img = new Images();
                $img->setImageURL($file);
                $figure->addLinkedFigureImage($img);
            }

            $figure->setSlug($slugger->slug($figure->getTitle()));
            // Pas nécessaire :
            // $figure = $form->getData();

            return $this->redirectToRoute('figure_details', ['id' => $figure->getId()]);
        }

        return $this->render('figure/figurenew.html.twig', [
            'controller_name' => 'FigureController',
            'formFigure' => $form->createView(),
            'editMode' => $figure->getId() ? true : false, 
            'figure' => $figure
        ]);
    }


    #[Route('/deleteimg/{id}', name: 'delete_img')]
    public function deleteImage(EntityManagerInterface $doctrine, Images $image){

            $doctrine->remove($image);
            $doctrine->flush();

            return $this->redirectToRoute('figure_mods', ['id' => $image->getLinkedFigure()->getId()]);
    }

}
