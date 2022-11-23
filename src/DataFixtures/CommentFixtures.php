<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 20; $i++){
            $comment = new Comment();
            $comment->setCreatedAt(new DateTimeImmutable())
                    ->setContent("<p>Contenu commentaire n°$i</p>")
                    ->setLinkedFigure(new Figure())
                    ->setAuthor(new User());
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
