<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    // /**
    //  * @return Formation[] Returns an array of Formation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Formation
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getformationeval()
    {
        $em=$this->getEntityManager();
        $commande='SELECT u.objet, u.type, u.objectif, u.nbParticipants,u.coutHj,u.nbJour,u.datePrevu,u.coutFin,u.path,u.imageName,u.id,AVG(p.note) as moy ,p.rapport 
              FROM App\Entity\Formation u INNER JOIN App\Entity\Evaluation p with u.id=p.idFormation GROUP BY p.idFormation';


        $query=$em->createQuery($commande);
        $result = $query->getResult();

        return $result;
    }
    public function getformationwithreviews()
    {
        $em=$this->getEntityManager();
        $commande='SELECT u.objet, u.type, u.objectif, u.nbParticipants,u.coutHj,u.nbJour,u.datePrevu,u.coutFin,u.path,u.imageName,u.categorie,u.id,(select count (o.id) from App\Entity\Evaluation o where o.idFormation=u.id  ) as nb
              FROM App\Entity\Formation u ';

        $query=$em->createQuery($commande);
        $result = $query->getResult();

        return $result;
    }
    public function search($input) {
        $em=$this->getEntityManager();
        $sql = "SELECT u.objet, u.type, u.objectif, u.nbParticipants,u.coutHj,u.nbJour,u.datePrevu,u.coutFin,u.path,u.imageName,u.categorie,u.id,(select count (o.id) from App\Entity\Evaluation o where o.idFormation=u.id  ) as nb FROM App\Entity\Formation u where u.objet 
        like :cex";

        $query=$em->createQuery($sql)->setParameter('cex',"%".$input."%");

        $result = $query->getResult();

        return $result;

    }
    public function Filtrage($input) {
        $em=$this->getEntityManager();
        $sql = "SELECT u.objet, u.type, u.objectif, u.nbParticipants,u.coutHj,u.nbJour,u.datePrevu,u.coutFin,u.path,u.imageName,u.categorie,u.id,(select count (o.id) from App\Entity\Evaluation o where o.idFormation=u.id  ) as nb FROM App\Entity\Formation u where u.categorie 
        = :cex";

        $query=$em->createQuery($sql)->setParameter('cex',$input);

        $result = $query->getResult();

        return $result;

    }
    public function Filtrage_reviewsdesc() {
        $em=$this->getEntityManager();
        $sql = "SELECT u.objet, u.type, u.objectif, u.nbParticipants,u.coutHj,u.nbJour,u.datePrevu,u.coutFin,u.path,u.imageName,u.categorie,u.id,(select count (o.id) from App\Entity\Evaluation o where o.idFormation=u.id  ) as nb FROM App\Entity\Formation u  
         order by nb desc";

        $query=$em->createQuery($sql);

        $result = $query->getResult();

        return $result;

    }
}
