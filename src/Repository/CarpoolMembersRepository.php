<?php

namespace App\Repository;

use App\Entity\Carpool;
use App\Entity\CarpoolMembers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CarpoolMembers>
 *
 * @method CarpoolMembers|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarpoolMembers|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarpoolMembers[]    findAll()
 * @method CarpoolMembers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarpoolMembersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarpoolMembers::class);
    }

    /**
     * @return integer Returns an array of CarpoolMembers objects
     */
    public function getMembersCount(Carpool $carpool): int
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.carpool = :val')
            ->andWhere('c.isAccepted = 1')
            ->setParameter('val', $carpool)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    public function findOneBySomeField($value): ?CarpoolMembers
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
