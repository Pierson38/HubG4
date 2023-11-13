<?php

namespace App\Repository;

use App\Entity\Carpool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carpool>
 *
 * @method Carpool|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carpool|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carpool[]    findAll()
 * @method Carpool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarpoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carpool::class);
    }

//    /**
//     * @return Carpool[] Returns an array of Carpool objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Carpool
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
