<?php

namespace App\Repository;

use App\Entity\Lbc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lbc>
 *
 * @method Lbc|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lbc|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lbc[]    findAll()
 * @method Lbc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LbcRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lbc::class);
    }

//    /**
//     * @return Lbc[] Returns an array of Lbc objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Lbc
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
