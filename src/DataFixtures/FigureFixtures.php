<?php

namespace App\DataFixtures;

use App\Entity\FigGroup;
use App\Entity\Figure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FigureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10; $i++){
            $figure = new Figure();
            $figure->setTitle("Figure $i")
                    ->setContent("<p>Contenu figure n°$i</p>")
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTime())
                    ->setFigGroup(new FigGroup())
                    ->setSlug("-nom-article-$i");
            $manager->persist($figure);
        }

        $manager->flush();
    }
}
