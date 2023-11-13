<?php

namespace App\Repository;

use App\Entity\MessagesImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessagesImages>
 *
 * @method MessagesImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessagesImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessagesImages[]    findAll()
 * @method MessagesImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessagesImages::class);
    }

//    /**
//     * @return MessagesImages[] Returns an array of MessagesImages objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MessagesImages
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
