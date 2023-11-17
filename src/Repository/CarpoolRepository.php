<?php

namespace App\Repository;

use App\Entity\Carpool;
use App\Entity\User;
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

   /* *
    * @return Carpool[] Returns an array of Carpool objects
    */
    public function getCarpoolAccepted(User $user): array
    {
        return $this->createQueryBuilder('c') // 'c' est un alias pour l'entité Carpool
            ->join('c.carpoolMembers', 'cm') // Jointure avec l'entité CarpoolMember
            ->where('cm.user = :user') // Filtrer pour n'inclure que les covoiturages où l'utilisateur donné est un membre
            ->andWhere('c.isAccepted = 1') // Filtrer pour n'inclure que les covoiturages acceptés
            ->setParameter('user', $user)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    

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
