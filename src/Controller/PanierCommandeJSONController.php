<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Formation;
use App\Entity\Panier;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PanierCommandeJSONController extends AbstractController
{
    /**
     * @Route("/displayJSON", name="panier_commandejson")
     */
    public function index(NormalizerInterface $normalizer,Request $request): Response
    {
        $em=$this->getDoctrine()->getManager();
        $commande=$em->getRepository(Commande::class)->findBy(['etat'=>'non valider','idClient'=>$request->get('id')]);
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
        $panier=$em->getRepository(Panier::class)->findOneBy(['idClient'=>$request->get('id')]);
        $jsoncontent=$normalizer->normalize($test,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsoncontent));
    }

    /**
     * @Route("/ajoutercommandejson", name="ajoutercommandejson")
     */
    public function ajoutercommande(Request $request,NormalizerInterface $normalizer): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Formation::class)->find($request->get('id'));
        $exist=$em->getRepository(Panier::class)->findOneBy(['idClient'=>$request->get('idClient')]);

        $panier=new Panier();
        if (!$exist){
            $panier->setIdClient($request->get('idClient'));
            $panier->setNombre(1);
            $panier->setPrixTotal($result->getCoutFin());
            $commande=new Commande();
            $commande->setPrix($result->getCoutFin());
            $commande->setEtat('non valider');
            $commande->setIdFormation($result);
            $commande->setIdClient($request->get('idClient'));
            $em->persist($panier);
            $em->persist($commande);
            $em->flush();
        }else
        {$commande=new Commande();
            $commande->setPrix($result->getCoutFin());
            $commande->setEtat('non valider');
            $commande->setIdFormation($result);
            $commande->setIdClient($request->get('idClient'));
            $exist->setNombre($exist->getNombre()+1);
            $exist->setPrixTotal($exist->getPrixTotal()+$result->getCoutFin());
            $em->persist($commande);

            $em->flush();
            $jsoncontent=$normalizer->normalize($commande,'json',['groups'=>'post:read']);
        }
        return new Response("Commande added successfully".json_encode($jsoncontent));
    }
    /**
     * @Route("/supprimercommandeJSON", name="supprimercommandeJSON")
     */
    public function supprimercommande( Request $request,NormalizerInterface $normalizer): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Commande::class)->find($request->get('id'));
        $exist=$em->getRepository(Panier::class)->findOneBy(['idClient'=>$request->get('idClient')]);
        $exist->setPrixTotal($exist->getPrixTotal()-$result->getPrix());
        $exist->setNombre($exist->getNombre()-1);
        $em->remove($result);
        $em->flush();
        $jsoncontent=$normalizer->normalize($result,'json',['groups'=>'post:read']);
        return new Response("Commande deleted successfully".json_encode($jsoncontent));
    }
    /**
     * @Route("/viderpanierJSON", name="viderpanierjson")
     */
    public function viderpanier(Request $request,NormalizerInterface $normalizer): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Commande::class)->findBy(['etat'=>'non valider','idClient'=>$request->get('idClient')]);
        foreach ($result as $x){
            $em->remove($x);
            $em->flush();
        }
        $exist=$em->getRepository(Panier::class)->findOneBy(['idClient'=>$request->get('idClient')]);
        $exist->setPrixTotal(0);
        $exist->setNombre(0);
        $em->flush();
        $jsoncontent=$normalizer->normalize($result,'json',['groups'=>'post:read']);
        return new Response("Commande deleted successfully".json_encode($jsoncontent));
    }
    /**
     * @Route("/validerJSON", name="validerjson")
     */
    public function valider(Request $request,NormalizerInterface $normalizer): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Commande::class)->findBy(['etat'=>'non valider','idClient'=>$request->get('idClient')]);
        $result2=$em->getRepository(Panier::class)->findOneBy(['idClient'=>$request->get('idClient')]);

        foreach ($result as $x){
            $x->setEtat('valider');
            $x->setDate(new \DateTime());
            $em->flush();
        }

        $result2->setPrixTotal(0);
        $result2->setNombre(0);

        $em->flush();

        $jsoncontent=$normalizer->normalize($result,'json',['groups'=>'post:read']);
        return new Response("payment done".json_encode($jsoncontent));
    }
}
