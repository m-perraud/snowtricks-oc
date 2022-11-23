<?php

namespace App\DataFixtures;

use App\Entity\FigGroup;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class FigGroupFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10; $i++){
            $group = new FigGroup();
            $group->setName("Groupe $i");
            $manager->persist($group);
        }

        $manager->flush();
    }
}
