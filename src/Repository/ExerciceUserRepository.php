<?php

namespace App\Repository;

use App\Entity\ExerciceUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExerciceUser>
 *
 * @method ExerciceUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciceUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciceUser[]    findAll()
 * @method ExerciceUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciceUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciceUser::class);
    }

    public function add(ExerciceUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExerciceUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByExerciceAndUser($exerciceid, $userid): ?ExerciceUser
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exerciceid = :exerciceId')
            ->andWhere('e.userid = :userId')
            ->setParameter('exerciceId', $exerciceid)
            ->setParameter('userId', $userid)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return ExerciceUser[] Returns an array of ExerciceUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ExerciceUser
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
