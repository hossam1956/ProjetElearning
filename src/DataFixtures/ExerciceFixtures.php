<?php

namespace App\DataFixtures;

use App\Entity\Exercice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class ExerciceFixtures extends Fixture  implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $exercice = new Exercice();
        $exercice->setTitre("Premier exercice");
        $manager->persist($exercice);


        $manager->flush();
    }

    public function getOrder(): int
    {
        // Return the order in which this fixture should be loaded
        // Lower values will be loaded first
        return 1;
    }
}
