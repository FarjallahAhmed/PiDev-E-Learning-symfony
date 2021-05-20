<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\Formation;
use App\Form\EvaluationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormationjsonController extends AbstractController
{
    /**
     * @Route("/formationjson", name="formationjson")
     */
    public function index(): Response
    {
        return $this->render('formationjson/index.html.twig', [
            'controller_name' => 'FormationjsonController',
        ]);
    }
    /**
     * @Route("/allformationsjson", name="allformationsjson")
     */
    public function index2(NormalizerInterface $normalizer): Response
    {
        $repository=$this->getDoctrine()->getRepository(Formation::class);
        $formations=$repository->findAll();
        $jsonContent=$normalizer->normalize($formations,'json',['groups'=>'post:read']);


       return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/Ajouterevaluationjson", name="Ajouterevaluationjson")
     */
    public function Ajouterevaluationjson(NormalizerInterface $normalizer,Request $request): Response

    {
        $Evaluation=new Evaluation();

        //$choix=$request->request->get('selectcateg');
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Formation::class)->find($request->get('id'));
        $evaluation_formation=$em->getRepository(Evaluation::class)->findBy(['idFormation'=>$result]);



            $note=$request->get('note');
            $description=$request->get('rapport');
            $Evaluation->setIdFormation($result);


            $Evaluation->setNote($note);
            $Evaluation->setRapport($description);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Evaluation);
            $entityManager->flush();
        $jsoncontent=$normalizer->normalize($Evaluation,'json',['groups'=>'post:read']);
            $test=1;
        return new Response("Commande added successfully".json_encode($jsoncontent));





    }

    /**
     * @Route("/recommendedjson", name="recommended")
     */
    public function formationrecommended(NormalizerInterface $normalizer): Response
    {       $em=$this->getDoctrine()->getManager();

        $al=$em->getRepository(Formation::class)->getformationeval();
        $jsoncontent=$normalizer->normalize($al,'json',['groups'=>'post:read']);
        return new Response("".json_encode($jsoncontent));

    }
}
