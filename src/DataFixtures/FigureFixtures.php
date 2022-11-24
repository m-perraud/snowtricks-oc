<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Figure;
use App\Entity\Images;
use App\Entity\Videos;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Entity\FigGroup;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class FigureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($h = 1; $h <= 5; $h++){
            $group = new FigGroup();
            $group->setName("Groupe $h");

            $manager->persist($group);

            $manager->flush();

            for($i = 1; $i <= 3; $i++){
                $figure = new Figure();
                $figure->setTitle("Figure $i")
                        ->setContent("<p>Contenu figure n°$i</p>")
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setUpdatedAt(new \DateTime())
                        ->setFigGroup($group)
                        ->setSlug("-nom-article-$i");

                $manager->persist($figure);

                $manager->flush();

                for ($j = 1; $j <= 3; $j++) {
                    $vid = new Videos();
                    $vid->setLinkedFigure($figure)
                        ->setVideoURL("http://placehold.it/350x350");
                    $manager->persist($vid);

                    $manager->flush();

                    $img = new Images();
                    $img->setLinkedFigure($figure)
                        ->setImageURL("http://placehold.it/350x350")
                        ->setMainImage($i === 1);
                    $manager->persist($img);

                    $manager->flush();
                }

                for ($k = 1; $k <= 2; $k++) {
                    $user = new User();
                    $user->setUsername("Username".$h.$k.uniqid())
                            ->setPassword("Password$k")
                            ->setProfilImage("http://placehold.it/50x50");
                    $manager->persist($user);

                    $manager->flush();

                    $comment = new Comment();
                    $comment->setCreatedAt(new DateTimeImmutable())
                            ->setContent("<p>Contenu commentaire n°$k</p>")
                            ->setLinkedFigure($figure)
                            ->setAuthor($user);
                    $manager->persist($comment);

                    $manager->flush();
                }
            }
        }
    }
}
