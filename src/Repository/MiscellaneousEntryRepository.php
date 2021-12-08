<?php

namespace App\Repository;

use App\Entity\Submission\Sections\MiscellaneousEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MiscellaneousEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method MiscellaneousEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method MiscellaneousEntry[]    findAll()
 * @method MiscellaneousEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MiscellaneousEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MiscellaneousEntry::class);
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
