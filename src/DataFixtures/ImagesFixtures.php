<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Images;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ImagesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10; $i++){
            $img = new Images();
            $img->setLinkedFigure(new Figure())
                ->setImageURL("http://placehold.it/350x350")
                ->setMainImage("1");
            $manager->persist($img);
        }

        $manager->flush();
    }
}
