<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Videos;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class VideoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10; $i++){
            $vid = new Videos();
            $vid->setLinkedFigure(new Figure())
                ->setVideoURL("http://placehold.it/350x350");
            $manager->persist($vid);
        }

        $manager->flush();
    }
}
