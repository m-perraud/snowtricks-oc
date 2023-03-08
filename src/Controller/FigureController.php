<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Images;
use App\Entity\Videos;
use App\Entity\Comment;
use App\Form\FigureType;
use App\Form\CommentType;
use App\Repository\FigureRepository;
use App\Service\VideoProcessingService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function figureNew(Figure $figure = null, Request $request, EntityManagerInterface $manager, VideoProcessingService $videoProc): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$figure) {
            $figure = new Figure();
        }
        $slugger = new AsciiSlugger();

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$figure->getId()) {
                $figure->setCreatedAt(new \DateTimeImmutable());
            } else {
                $figure->setUpdatedAt(new \DateTimeImmutable());
            }

            $images = $form->get('images')->getData();
            $videos = $form->get('videos')->getData();
            $videos = explode(',', $videos);


            $haveMainImage = false;

            foreach ($images as $image) {
                $file = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                $img = new Images();

                if (!$haveMainImage) {
                    $img->setMainImage(true);
                    $haveMainImage = true;
                }

                $img->setMainImage(false);
                $img->setImageURL('/images/figures/' . $file);
                $img->setLinkedFigure($figure);
                $manager->persist($img);
            }

            foreach ($videos as $video) {
                $videoURL = $videoProc->cleanURL($video);

                $vid = new Videos();
                $vid->setVideoURL($videoURL);
                $vid->setLinkedFigure($figure);
                $manager->persist($vid);
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
    public function deleteImage(EntityManagerInterface $doctrine, Images $image, Request $request)
    {

        $csrfToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete', $csrfToken)) {
            $doctrine->remove($image);
            $doctrine->flush();
        }

        return $this->redirectToRoute('figure_mods', ['id' => $image->getLinkedFigure()->getId()]);
    }

    #[Route('/deletefig/{id}', name: 'delete_fig')]
    public function deleteFigure(EntityManagerInterface $doctrine, Figure $figure, Request $request, int $id)
    {

        $doctrine->remove($figure);
        $doctrine->flush();

        return $this->redirectToRoute('app_home');
    }

    #[Route('/deletevid/{id}', methods: ['GET', 'DELETE'], name: 'delete_vid')]
    public function deleteVideo(EntityManagerInterface $doctrine, Videos $video, Request $request, int $id): Response
    {

        $doctrine->remove($video);
        $doctrine->flush();

        return $this->redirectToRoute('figure_details', ['slug' => $figure->getSlug()]);
    }
}
