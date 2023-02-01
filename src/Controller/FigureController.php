<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Images;
use App\Entity\Videos;
use App\Entity\Comment;
use App\Form\FigureType;
use App\Form\CommentType;
use App\Repository\FigureRepository;
use App\Repository\ImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Provider\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{

    #[Route('/figure/{slug}', name: 'figure_details')]
    public function figureDetails(FigureRepository $repo, string $slug, Request $request, EntityManagerInterface $manager): Response
    {
    $figure = $repo->findOneBy(['slug' => $slug]);

    $comment = new Comment();

    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setLinkedFigure($figure);
            // Avec le $this->getUser() on a directement le user connecté 
            $comment->setAuthor($this->getUser());

        $manager->persist($comment);
        $manager->flush();

        return $this->redirectToRoute('figure_details', ['slug' => $figure->getSlug()]);
    }

    return $this->render('figure/figuredetails.html.twig', [
        'controller_name' => 'FigureController',
        'formComment' => $form->createView(),
        'figures' => $figure
    ]);
}


    #[Route('/figurenew', name: 'figure_new')]
    #[Route('/figure/{id}/edit', name: 'figure_mods')]
    public function figureNew(Figure $figure = null, Request $request, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
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
                $figure->setUpdatedAt(new \DateTimeImmutable());
            }

            $images = $form->get('images')->getData();

            // Il faudra vérifier si on a déjà une main ou pas.
            
            $haveMainImage = false;

            foreach($images as $image){
                $file = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                $img = new Images();

                if(!$haveMainImage){
                    $img->setMainImage(true);
                    $haveMainImage = true;
                }

                $img->setImageURL($file);
                $img->setLinkedFigure($figure);
                $manager->persist($img);
            }

            $figure->setSlug($slugger->slug($figure->getTitle()));
            $manager->flush();

            return $this->redirectToRoute('figure_details', ['slug' => $figure->getSlug()]);
        }

        return $this->render('figure/figurenew.html.twig', [
            'controller_name' => 'FigureController',
            'formFigure' => $form->createView(),
            'editMode' => $figure->getId() ? true : false, 
            'figure' => $figure
        ]);
    }


    #[Route('/deleteimg/{id}', name: 'delete_img')]
    public function deleteImage(EntityManagerInterface $doctrine, Images $image, Request $request){

        $csrfToken = $request->request->get('token');

        if($this->isCsrfTokenValid('delete', $csrfToken)){
            $doctrine->remove($image);
            $doctrine->flush();
        }

            return $this->redirectToRoute('figure_mods', ['id' => $image->getLinkedFigure()->getId()]);
    }

}