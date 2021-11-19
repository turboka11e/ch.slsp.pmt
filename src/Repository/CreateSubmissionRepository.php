<?php

namespace App\Repository;

use App\Entity\CreateSubmission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CreateSubmission|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreateSubmission|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreateSubmission[]    findAll()
 * @method CreateSubmission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreateSubmissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreateSubmission::class);
    }

    // /**
    //  * @return CreateSubmission[] Returns an array of CreateSubmission objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CreateSubmission
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
