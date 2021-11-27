<?php

namespace App\Repository;

use App\Entity\Choices\CategoryChoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryChoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryChoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryChoice[]    findAll()
 * @method CategoryChoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryChoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryChoice::class);
    }

    // /**
    //  * @return CategoryChoice[] Returns an array of CategoryChoice objects
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
    public function findOneBySomeField($value): ?CategoryChoice
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
