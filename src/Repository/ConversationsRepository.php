<?php

namespace App\Repository;

use App\Entity\Conversations;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversations>
 *
 * @method Conversations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversations[]    findAll()
 * @method Conversations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversations::class);
    }

   /**
    * @return Conversations[] Returns an array of Conversations objects
    */
   public function getAllUserConversations(User $user): array
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.fromUser = :val')
           ->orWhere('c.toUser = :val')
           ->setParameter('val', $user)
           ->orderBy('c.updatedAt', 'DESC')
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Conversations
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
