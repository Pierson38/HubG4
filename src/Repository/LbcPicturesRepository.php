<?php

namespace App\Repository;

use App\Entity\LbcPictures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LbcPictures>
 *
 * @method LbcPictures|null find($id, $lockMode = null, $lockVersion = null)
 * @method LbcPictures|null findOneBy(array $criteria, array $orderBy = null)
 * @method LbcPictures[]    findAll()
 * @method LbcPictures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LbcPicturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LbcPictures::class);
    }

//    /**
//     * @return LbcPictures[] Returns an array of LbcPictures objects
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

//    public function findOneBySomeField($value): ?LbcPictures
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
