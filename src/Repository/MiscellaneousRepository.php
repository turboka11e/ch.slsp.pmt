<?php

namespace App\Repository;

use App\Entity\Miscellaneous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Miscellaneous|null find($id, $lockMode = null, $lockVersion = null)
 * @method Miscellaneous|null findOneBy(array $criteria, array $orderBy = null)
 * @method Miscellaneous[]    findAll()
 * @method Miscellaneous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MiscellaneousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Miscellaneous::class);
    }

    // /**
    //  * @return Miscellaneous[] Returns an array of Miscellaneous objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Miscellaneous
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
