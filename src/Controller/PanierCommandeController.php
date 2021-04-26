<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Commande;
use App\Entity\Panier;
use App\Form\AchatType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Formation;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\Stripe;

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
    /**
     * @Route("/create-checkout-session", name="checkout")
     */
    public function checkout(Request $request): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Commande::class)->findBy(['etat'=>'non valider','idClient'=>1]);
        $test=[];
        foreach ($result as $x) {
            $test[] =
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $x->getPrix()*100,
                        'product_data' => [
                            'name' => $x->getIdFormation()->getObjet(),
                        ],
                    ],
                    'quantity' => 1,
                ];


        }
        \Stripe\Stripe::setApiKey('sk_test_UyGOFESdBdhmN6TtUwAY3Knn00fbOUdEE4');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],

            'line_items' => $test,


            'mode' => 'payment',
            'success_url' => $this->generateUrl('valider',[],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => 'https://example.com/cancel',
        ]);

        return new JsonResponse([ 'id' => $session->id ]);

    }
    /**
     * @Route("/finaliser", name="finaliser")
     */
    public function finalisercommande(Request $request): Response
    {
        $achat = new Achat();
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Commande::class)->findBy(['etat'=>'non valider','idClient'=>1]);
        $result2=$em->getRepository(Panier::class)->findOneBy(['idClient'=>1]);
        if ($form->isSubmitted() && $form->isValid()) {


            $achat->setPrixtotal($result2->getPrixTotal());
            $achat->setNbarticle($result2->getNombre());

            $em->persist($achat);
            $em->flush();
            return $this->render('panier_commande/payer.html.twig', [

            ]);
        }
        return $this->render('panier_commande/finalisercommande.html.twig', [
            'form' => $form->createView(),
        ]);

    }
    /**
     * @Route("/history", name="history")
     */
    public function archive(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commande::class)->findBy(['etat' => 'valider', 'idClient' => 1]);

        $test = [];
        foreach ($commande as $x) {
            $test[] = [
                'id' => $x->getId(),
                'prix' => $x->getPrix(),
                'objet' => $x->getIdFormation()->getObjet(),
                'type' => $x->getIdFormation()->getType(),
                'date' => $x->getDate(),

            ];


        }
        return $this->render('panier_commande/history.html.twig', [
            'affichage'=>$test,

        ]);
    }
    /**
     * @Route("/achatback", name="achatback")
     */
    public function afficherachatback(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Achat::class)->findAll();
        return $this->render('panier_commande/achatback.html.twig', [
            'result' => $result]);

    }

    /**
     * @Route("/searchachat", name="searchachat")
     */
    public function search(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $input = $request->get('search');
        $tab = $em->getRepository(Achat::class)->search($input);
        return $this->render('panier_commande/achatback.html.twig', [

            'result' => $tab,
        ]);
    }
    /**
     * @Route("/sort", name="triachat")
     */
    public function sort(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tab = $em->getRepository(Achat::class)->sort();
        return $this->render('panier_commande/achatback.html.twig', [

            'result' => $tab,
        ]);
    }
    /**
     * @Route("/valider", name="valider")
     */
    public function valider(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Commande::class)->findBy(['etat'=>'non valider','idClient'=>1]);
        $result2=$em->getRepository(Panier::class)->findOneBy(['idClient'=>1]);

        foreach ($result as $x){
            $x->setEtat('valider');
            $x->setDate(new \DateTime());
            $em->flush();
        }

        $result2->setPrixTotal(0);
        $result2->setNombre(0);

        $em->flush();

        return $this->redirect($this->generateUrl("formationfront"));
    }
}
