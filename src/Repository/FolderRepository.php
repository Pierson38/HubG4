<?php

namespace App\Repository;

use App\Entity\Folder;
use App\Entity\Promo;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Folder>
 *
 * @method Folder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Folder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Folder[]    findAll()
 * @method Folder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FolderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Folder::class);
    }

   /**
    * @return Folder[] Returns an array of Folder objects
    */
   public function getBaseUserFolders(User $user, Promo $promo): array
   {
       return $this->createQueryBuilder('f')
            ->leftJoin('f.permissions', 'p')
            ->where('p.user = :user')
            ->orWhere('p.promo = :promo')
            ->setParameter('user', $user)
            ->setParameter('promo', $promo)
            ->getQuery()
            ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Folder
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
