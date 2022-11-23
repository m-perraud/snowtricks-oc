<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Role\Role;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10; $i++){
            $user = new User();
            $user->setUsername("Username$i")
                    ->setPassword("Password$i</p>")
                    ->setProfilImage("http://placehold.it/350x350");
            $manager->persist($user);
        }

        $manager->flush();
    }
}
