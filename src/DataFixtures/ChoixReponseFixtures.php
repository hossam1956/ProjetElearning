<?php

namespace App\DataFixtures;

use App\Entity\ChoixReponse;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ChoixReponseFixtures extends Fixture
{

    // private $entityManager;

    // public function __construct(ObjectManager $entityManager)
    // {
    //     $this->entityManager = $entityManager;
    // }

    public function load(ObjectManager $manager): void
    {
        // for ($i = 1; $i <= 10; $i++) {
        //     $question = $manager->find(Question::class, $i);
        //     for ($j = 0; $j < 4; $j++) {
        //         $choix = new ChoixReponse();
        //         $choix->setChoix("choix number $j");
        //         $choix->setQuestion($question);
        //         $manager->persist($choix);
        //     }
        // }

        $manager->flush();
    }
}
