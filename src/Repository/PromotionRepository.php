<?php

namespace App\Repository;

use App\Entity\Promotion;
use App\Entity\Workshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calendar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calendar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calendar[]    findAll()
 * @method Calendar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    // /**
    //  * @return Calendar[] Returns an array of Calendar objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Calendar
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function affectPromo()
    {
        $em=$this->getEntityManager();
        $commande='SELECT u.objet, u.type, u.objectif, u.nbParticipants,u.coutHj,u.nbJour,u.datePrevu,u.coutFin,u.path,u.id ,p.prix 
              FROM App\Entity\Formation u INNER JOIN App\Entity\Promotion p with u.id=p.idFormation';


        $query=$em->createQuery($commande);
        $result = $query->getResult();

        return $result;
    }
}
