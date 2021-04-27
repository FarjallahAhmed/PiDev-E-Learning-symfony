<?php

namespace App\Repository;

use App\Entity\Formation;
use App\Entity\Promotion;
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

     /**
      * @return Calendar[] Returns an array of Calendar objects
      */

    public function findByPrix($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.prix = :val')
            ->setParameter('val', $value)
            //->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDatedebut($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.datedebut = :val')
            ->setParameter('val', $value)
            //->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }


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

    public function statisPromo(){

        $query = $this->createQueryBuilder('p')
            ->select('AVG(p.prix) as moy ,Max(p.prix) as maxPourcentage,f.type')
            ->innerJoin(Formation::class,'f')

            ->andWhere('f.id = p.idFormation ')
            ->groupBy('f.type')
            ;
        return $query->getQuery()->getResult();
    }


    public function findAllWithSearch(?string $term)
    {
        $qb = $this->createQueryBuilder('p');
        if ($term) {
            $qb->andWhere('p.prix LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }
        return $qb
        ->getQuery()
        ->getResult();

    }

    public function searchMulti($dateD = null,$pourc = null,$dateF = null){
        $query = $this->createQueryBuilder('p')
            ->select("u.objet, u.type, u.objectif, u.nbParticipants,u.coutHj,u.nbJour,u.datePrevu,u.coutFin,u.path,u.id ,p.prix,p.datedebut,p.datefin")
            ->innerJoin(Formation::class,'u')
            ->andWhere('u.id = p.idFormation ')
        ;
        // On filtre les données
        if($dateD != null || $pourc != null || $dateF != null){
            $query
                ->andWhere('p.prix = :pourc OR p.datedebut =:dateD OR p.datefin = :dateF ')
                ->setParameters(['dateD'=>(new \DateTime($dateD ))->format('Y-m-d') , 'dateF'=>(new \DateTime($dateF ))->format('Y-m-d'),'pourc'=>$pourc])

                ;
        }
        return $query->getQuery()->getResult();
    }

}
