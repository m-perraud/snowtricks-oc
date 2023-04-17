<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Images;
use App\Entity\Videos;
use App\Form\FigureType;
use App\Service\VideoProcessingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function PHPUnit\Framework\isEmpty;

class EditsController extends AbstractController
{
    #[Route('/figure/{id}/edit', name: 'figure_mods')]
    public function figureNew(Figure $figure = null, Request $request, EntityManagerInterface $manager, VideoProcessingService $videoProc): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setUpdatedAt(new \DateTimeImmutable());

            $images = $form->get('images')->getData();
            $videos = $form->get('videos')->getData();

            if(!isEmpty($videos)){
                $videos = explode(',', $videos);

                foreach ($videos as $video) {
                    $videoURL = $videoProc->cleanURL($video);
    
                    $vid = new Videos();
                    $vid->setVideoURL($videoURL);
                    $vid->setLinkedFigure($figure);
                    $manager->persist($vid);
                }
            }
            
            $haveMainImage = false;

            if (!$images && count($figure->getLinkedFigureImages()) == 0 ) {
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

            $manager->flush();
            return $this->redirectToRoute('figure_mods', ['id' => $figure->getId()]);
        }

        return $this->render('edits/figuremods.html.twig', [
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
    public function deleteFigure(EntityManagerInterface $doctrine, Figure $figure)
    {
        $doctrine->remove($figure);
        $doctrine->flush();
        return $this->redirectToRoute('app_home');
    }

    #[Route('/deletevid/{id}', methods: ['GET', 'DELETE'], name: 'delete_vid')]
    public function deleteVideo(EntityManagerInterface $doctrine, Videos $video): Response
    {
        $doctrine->remove($video);
        $doctrine->flush();
        return $this->redirectToRoute('figure_mods', ['id' => $video->getLinkedFigure()->getId()]);
    }
}
