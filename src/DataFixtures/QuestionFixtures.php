<?php

namespace App\DataFixtures;

use App\Entity\Exercice;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class QuestionFixtures extends Fixture implements OrderedFixtureInterface
{
    // private $entityManager;
    // private $doctrine;

    // public function __construct(ManagerRegistry $doctrine)
    // {
    //     $this->doctrine = $doctrine;
    // }

    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 10; $i++) {
            $question = new Question();
            $question->setQuestion("Question number $i");
            $question->setReponse($i);
            $question->setExercice($manager->getRepository(Exercice::class)->find(1));
            $manager->persist($question);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        // Return the order in which this fixture should be loaded
        // Lower values will be loaded first
        return 2;
    }
}
