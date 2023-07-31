<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    // /**
    //  * @return Question[]
    //  */
    // public function StatsQuestionByExercice($exercice_id)
    // {
    //     $qb = $this->createQueryBuilder(alias: 'q')
    //         ->select(select: '*');
    //     $this->addIntervaleAge($qb, $ageMin, $ageMax);
    //     return $qb->getQuery()
    //         ->getScalarResult();
    // }


    // private function addIntervaleAge(QueryBuilder $qb, $ageMin, $ageMax)
    // {
    //     // on peut tester ici si age < 0 ...
    //     $qb->andWhere('p.age >= :ageMin and p.age <= :ageMax')
    //         ->setParameters(['ageMin' => $ageMin, 'ageMax' => $ageMax]);
    // }

    //    /**
    //     * @return Question[] Returns an array of Question objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Question
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
