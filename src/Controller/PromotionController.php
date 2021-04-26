<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromotionController extends AbstractController
{
    /**
     * @Route("/promotion", name="promotion")
     */
    public function index(PromotionRepository $repo,Request $request): Response
    {
        //$promotions = $repo->findAll();

        $q = $request->query->get('search');
        $promotions = $repo->findAllWithSearch($q);
        return $this->render('promotion/index.html.twig', [
            'controller_name' => 'PromotionController',
            'promotions' => $promotions,
        ]);
    }

    /**
     * @Route("/addPromo", name="addPromotion")
     */
    public function addPromo(Request $request): Response
    {
        $promo = new Promotion();
        $form = $this->createForm(PromotionType::class,$promo);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();

            $promo->setIdFormation($data->getFormation()->getId());
            $em->persist($promo);
            $em->flush();
            $this->addFlash('success', 'Promotion Ajouter avec sucess!');

            return $this->redirectToRoute('promotion');
        }

        return $this->render('promotion/addPromotion.html.twig', [
            'formPromo' => $form->createView(),
        ]);
    }
    /**
     * @Route("/promotion/{id}/editPromotion", name="editPromotion")
     */
    public function edit(Request $request,PromotionRepository $repo,$id): Response
    {
        $promo = $repo->find($id);
        $form = $this->createForm(PromotionType::class,$promo);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $em->persist($promo);
            $em->flush();
            $this->addFlash('success', 'Edit Promotion avec sucess!');

            return $this->redirectToRoute('promotion');
        }
        return $this->render('promotion/addPromotion.html.twig', [
            'formPromo' => $form->createView(),

        ]);
    }
    /**
     * @Route("/promotion/{id}/deletePromotion", name="deletePromotion")
     */
    public function deleteEvent(Request $request,PromotionRepository $repo,$id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $calendar = $repo->find($id);
        $entityManager->remove($calendar);
        $entityManager->flush();
        $this->addFlash('success', 'Delete Promotion avec sucess!');

        return $this->redirectToRoute('promotion');
    }

    /**
     * @Route("/promotion/promotionFront", name="promotionFront")
     */
    public function Front(PromotionRepository $repo,Request $request): Response
    {
//        $promotions = $repo->affectPromo();
        $date = new \DateTime('now');
        $promotionsSearch = $repo->searchMulti(null,null,null);
        $promotionslast = [];

        $pourc = $request->get("pourcentage");
        $dateD = $request->get("dateD");
        $dateF = $request->get("dateF");

        $promotions = $repo->searchMulti($dateD,$pourc,$dateF);



        foreach($promotionsSearch as $promo){
            $diff = date_diff($date,$promo["datefin"] );
            if ($diff->d<3){
                $promotionslast[] = $promo ;

            }
        }

        return $this->render('promotion/showPromoFront.html.twig', [
            'controller_name' => 'PromotionController',
            'promotions' => $promotions,
            'promolast' =>  $promotionslast
        ]);
    }

    /**
     * @Route("/promotion/stats",name="statsPromo")
     */
    public function stats(PromotionRepository $repo){

        $promotions = $repo->statisPromo();
        $promotionsMoy = [];
        $formationType = [];
        $maxPourcentage = [];
        foreach ($promotions as $event){
            $formationType[] = $event['type'];
            $promotionsMoy[] = $event['moy'];
            $maxPourcentage[] = $event['maxPourcentage'];
        }


        return $this->render('promotion/stats.html.twig',[
            'formationType' => json_encode($formationType),
            'promotionsMoy' => json_encode($promotionsMoy),
            'maxPourcentage' => json_encode($maxPourcentage),
        ]);

    }
}
