<?php

namespace App\Repository;

use App\Entity\Messages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Messages>
 *
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messages::class);
    }

    public function getNotReadCount($user, $conversation): int
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.conversation = :conversation')
            ->andWhere('m.userAccount != :user')
            ->andWhere('m.isRead = :read')
            ->setParameter('user', $user)
            ->setParameter('conversation', $conversation)
            ->setParameter('read', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function setNotReadRead($user, $conversation): int
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.conversation = :conversation')
            ->andWhere('m.userAccount != :user')
            ->andWhere('m.isRead = :read')
            ->setParameter('user', $user)
            ->setParameter('conversation', $conversation)
            ->setParameter('read', false)
            ->update()
            ->set('m.isRead', ':readTrue')
            ->setParameter('readTrue', true)
            ->getQuery()
            ->execute();
    }

    /**
     * @return Messages Returns an array of Conversations objects
     */
    public function getLastMessage($conversation): ?Messages
    {
        $query = $this->createQueryBuilder('m')
            ->where('m.conversation = :conversation')
            ->setParameter('conversation', $conversation)
            ->orderBy('m.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
            return $query ? $query[0] : null;
    }

//    /**
//     * @return Messages[] Returns an array of Messages objects
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

//    public function findOneBySomeField($value): ?Messages
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
