<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($k = 1; $k <= 600; ++$k) {
            $message = new Comment();
            $message->setAuthor("author n° $k")
                ->setText("Contenu du commentaires n°$k")
                ->setCreatedAt(new \DateTime())
                ->setEmail("lol@gmail.com")
                ->setConference($this->getReference(sprintf('trick-%d', ($k % 10) + 1)));
            $manager->persist($message);


            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ConferenceFixtures::class,
        ];
    }
}