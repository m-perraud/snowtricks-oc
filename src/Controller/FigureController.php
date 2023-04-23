<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Images;
use App\Entity\Videos;
use App\Entity\Comment;
use App\Form\FigureType;
use App\Form\CommentType;
use App\Repository\FigureRepository;
use App\Repository\CommentRepository;
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
    public function figureDetails(FigureRepository $repo, CommentRepository $repoComment, string $slug, Request $request, EntityManagerInterface $manager): Response
    {
        $figure = $repo->findOneBy(['slug' => $slug]);

        if(is_null($figure)){
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        }

        $page = $request->query->getInt('page', 1);
        $comments = $repoComment->findCommentsPaginated($figure, $page, 6);

        //dd($comments);

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
            'figure' => $figure,
            'comments' => $comments
        ]);
    }


    #[Route('/figurenew', name: 'figure_new')]
    public function figureNew(Figure $figure = null, Request $request, EntityManagerInterface $manager, VideoProcessingService $videoProc): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $figure = new Figure();
        $slugger = new AsciiSlugger();
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setCreatedAt(new \DateTimeImmutable());

            $images = $form->get('images')->getData();
            $videos = $form->get('videos')->getData();
            $videos = explode(',', $videos);
            $haveMainImage = false;

            if (!$images) {
                $img = new Images();
                $img->setImageURL('/images/figures/fixture.jpg');
                $img->setMainImage(true);
                $img->setLinkedFigure($figure);
                $manager->persist($img);
            }

            foreach ($images as $image) {
                $file = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                $img = new Images();
                $img->setMainImage(false);

                if (!$haveMainImage) {
                    $img->setMainImage(true);
                    $haveMainImage = true;
                }

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

            $this->addFlash('success', 'Votre figure a été créée avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('figure/figurenew.html.twig', [
            'controller_name' => 'FigureController',
            'formFigure' => $form->createView(),
            'editMode' => $figure->getId() ? true : false,
            'figure' => $figure
        ]);
    }


    #[Route('/comments', name: 'app_comments')]
    public function commentsPagination(Request $request, CommentRepository $repo, Figure $figure): Response
    {
        $page = $request->query->getInt('page', 1);
        $comments = $repo->findCommentsPaginated($figure, $page, 6);

        return $this->render('home/comments.html.twig', [
            'controller_name' => 'FigureController',
            'comments' => $comments
        ]);
    }
}
