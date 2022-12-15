<?php

namespace App\DataFixtures;

use Faker\Factory;
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
        $faker = Factory::create();
                for($h = 1; $h <= 5; $h++){
            $group = new FigGroup();
            $group->setName($faker->word());

            $manager->persist($group);

            $manager->flush();

            for($i = 1; $i <= 3; $i++){
                $figure = new Figure();
                $figure->setTitle($faker->word())
                        ->setContent($faker->text())
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setUpdatedAt(new \DateTime())
                        ->setFigGroup($group)
                        ->setSlug($faker->slug());

                $manager->persist($figure);

                $manager->flush();

                for ($j = 1; $j <= 3; $j++) {
                    $vid = new Videos();
                    $vid->setLinkedFigure($figure)
                        ->setVideoURL($faker->imageUrl(350, 200));
                    $manager->persist($vid);

                    $manager->flush();

                    $img = new Images();
                    $img->setLinkedFigure($figure)
                        ->setImageURL($faker->imageUrl(100, 100))
                        ->setMainImage($j === 1);
                    $manager->persist($img);

                    $manager->flush();
                }

                for ($k = 1; $k <= 2; $k++) {
                    $user = new User();
                    $user->setUsername($faker->userName())
                            ->setPassword($faker->password())
                            ->setProfilImage($faker->imageUrl(50, 50))
                            ->setUserMail($faker->email());
                    $manager->persist($user);

                    $manager->flush();

                    $comment = new Comment();
                    $comment->setCreatedAt(new DateTimeImmutable())
                            ->setContent($faker->text())
                            ->setLinkedFigure($figure)
                            ->setAuthor($user);
                    $manager->persist($comment);

                    $manager->flush();
                }
            }
        }
    }
}
