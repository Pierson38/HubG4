<?php

namespace App\Repository;

use App\Entity\PostsCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostsCategories>
 *
 * @method PostsCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostsCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostsCategories[]    findAll()
 * @method PostsCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostsCategories::class);
    }

   /**
    * @return PostsCategories[] Returns an array of PostsCategories objects
    */
   public function getAllBaseCategories(): array
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.categoryParent IS NULL')
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?PostsCategories
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
