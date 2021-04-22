<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Formation;

class PanierCommandeController extends AbstractController
{
    /**
     * @Route("/panier/commande", name="panier_commande")
     */
    public function index(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $commande=$em->getRepository(Commande::class)->findBy(['etat'=>'non valider','idClient'=>1]);
        //$test=$commande->getIdFormation()->getObjet();
        $test=[];
        foreach ($commande as $x){
            $test[] = [
                'id' => $x->getId(),
                'prix' => $x->getPrix(),
                'objet'=>$x->getIdFormation()->getObjet(),
                'type' => $x->getIdFormation()->getType(),

            ];

    }
        $panier=$em->getRepository(Panier::class)->findOneBy(['idClient'=>1]);
        return $this->render('panier_commande/panier.html.twig', [
            'controller_name' => 'PanierCommandeController',
            'affichagepanier'=>$test,
            'commande'=>$commande,
            'panier'=>$panier
        ]);
    }
    /**
     * @Route("/ajoutercommande/{id}", name="ajoutercommande")
     */
    public function ajoutercommande(int $id): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Formation::class)->find($id);
        $exist=$em->getRepository(Panier::class)->findOneBy(['idClient'=>1]);
        $panier=new Panier();
        if (!$exist){
            $panier->setIdClient(1);
            $panier->setNombre(1);
            $panier->setPrixTotal($result->getCoutFin());
            $commande=new Commande();
            $commande->setPrix($result->getCoutFin());
            $commande->setEtat('non valider');
            $commande->setIdFormation($result);
            $commande->setIdClient(1);
            $em->persist($panier);
            $em->persist($commande);
            $em->flush();
        }else
        {$commande=new Commande();
        $commande->setPrix($result->getCoutFin());
        $commande->setEtat('non valider');
        $commande->setIdFormation($result);
        $commande->setIdClient(1);
        $exist->setNombre($exist->getNombre()+1);
        $exist->setPrixTotal($exist->getPrixTotal()+$result->getCoutFin());
        $em->persist($commande);

        $em->flush();}
        return $this->redirect($this->generateUrl("formationfront"));
    }
    /**
     * @Route("/supprimercommande/{id}", name="supprimercommande")
     */
    public function supprimercommande(int $id): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Commande::class)->find($id);
        $exist=$em->getRepository(Panier::class)->findOneBy(['idClient'=>1]);
        $exist->setPrixTotal($exist->getPrixTotal()-$result->getPrix());
        $exist->setNombre($exist->getNombre()-1);
        $em->remove($result);
        $em->flush();
        return $this->redirect($this->generateUrl("panier_commande"));
    }
    /**
     * @Route("/viderpanier", name="viderpanier")
     */
    public function viderpanier(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Commande::class)->findBy(['etat'=>'non valider','idClient'=>1]);
        foreach ($result as $x){
            $em->remove($x);
            $em->flush();
        }
        $exist=$em->getRepository(Panier::class)->findOneBy(['idClient'=>1]);
        $exist->setPrixTotal(0);
        $exist->setNombre(0);
        $em->flush();
        return $this->redirect($this->generateUrl("panier_commande"));
    }
    /**
     * @Route("/showallback", name="showallback")
     */
    public function affichercommandeback(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Commande::class)->findAll();
        return $this->render('panier_commande/showcommand.html.twig', [
            'result' => $result]);

    }

}
