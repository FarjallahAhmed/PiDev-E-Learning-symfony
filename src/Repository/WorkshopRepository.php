<?php

namespace App\Repository;

use App\Entity\Workshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calendar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calendar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calendar[]    findAll()
 * @method Calendar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkshopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workshop::class);
    }

    public function countByDate(){
        $query = $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.datedebut,1,10) as dateD , count(a) as count,a.type as type' )
            ->groupBy('type')
            ;
        return $query->getQuery()->getResult();

    }

    public function countByLike(){
        $query = $this->createQueryBuilder('a')
            ->select('a.type as typeW , SUM(a.hearts) as count' )
            ->groupBy('typeW')
        ;
        return $query->getQuery()->getResult();

    }

    public function findAllWithSearch(?string $term)
    {
        $qb = $this->createQueryBuilder('e');
        if ($term) {
            $qb->andWhere('e.nomevent LIKE :term OR e.type LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }
        return $qb
            ->getQuery()
            ->getResult();

    }

    public function getTotalEvent($filters = null){
        $query = $this->createQueryBuilder('a')
                ->select("a")
            ;
        // On filtre les données
        if($filters != null){
            $query->andWhere('a.type IN(:type)')
                ->setParameter(':type', array_values($filters));
        }

        return $query->getQuery()->getResult();
    }

    public function pagination($filters = null){
        $query = $this->createQueryBuilder('a')
            ->select("a")
        ;
        // On filtre les données
        if($filters != null){
            $query->andWhere('a.type IN(:type)')
                ->setParameter(':type', array_values($filters));
        }

        return $query->getQuery();
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
}
