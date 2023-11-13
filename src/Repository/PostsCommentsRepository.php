<?php

namespace App\Repository;

use App\Entity\PostsComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostsComments>
 *
 * @method PostsComments|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostsComments|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostsComments[]    findAll()
 * @method PostsComments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsCommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostsComments::class);
    }

//    /**
//     * @return PostsComments[] Returns an array of PostsComments objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PostsComments
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
