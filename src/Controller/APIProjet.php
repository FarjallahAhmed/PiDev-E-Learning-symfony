<?php


namespace App\Controller;

use App\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use \Twilio\Rest\Client;


class APIProjet extends AbstractController
{

    private $twilio;

    public function __construct(Client $twilio) {
       $this->twilio = $twilio;

     }
    /******************Modifier Projet*****************************************/
    /**
     * @Route("/APIProjet/updateProjet", name="update_projet")
     * @Method("PUT")
     */
    public function modifierProjetAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $projet = $this->getDoctrine()->getManager()
            ->getRepository(Projet::class)
            ->find($request->get("idProjet"));

        $projet->setNom($request->get("nomprojet"));
        $projet->setSujet($request->get("sujetprojet"));
        $projet->setDescription($request->get("description"));


        $em->persist($projet);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($projet);
        return new JsonResponse("Projet a ete modifiee avec success.");

    }
    /**
     * @Route("/APIProjet/AllProjet",name="AllProjet")
     */
    public function AllProjet(Request $request, NormalizerInterface $normalizer)
    {

        $repo = $this->getDoctrine()->getRepository(Projet::class);
        $events = $repo->findAll();


        $jsonContent = $normalizer->normalize($events, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));

    }


    /**
     * @Route("/APIProjet/getProjet/{idProjet}",name="getProjet")
     */
    public function ProjetId(Request $request,NormalizerInterface $normalizer,$idProjet){

        $repo = $this->getDoctrine()->getRepository(Projet::class);
        $events = $repo->find($idProjet);

        $jsonContent = $normalizer->normalize($events,'json',['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("APIProjet/addProjet",name="APIaddProjet")
     */
    public function APIAddProjet(Request $request,NormalizerInterface $normalizer){

        $em = $this->getDoctrine()->getManager();
        $event = new Projet() ;
        $event->setNom($request->get('nomprojet'));
        $event->setSujet($request->get('sujetprojet'));
        $event->setDescription($request->get('description'));
        $event->setDatec(new \DateTime($request->get('datecreation')));

        $em->persist($event);
        $em->flush();
        $jsonContent = $normalizer->normalize($event,'json',['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));

    }




    /**
     * @Route("/APIProjet/deleteProjet/{idProjet}",name="deleteProjetAPI")
     */
    public function DeleteProjet(Request $request,NormalizerInterface $normalizer,$idProjet){

        $entityManager = $this->getDoctrine()->getManager();
        $event = $entityManager->getRepository(Projet::class)->find($idProjet);
        $entityManager->remove($event);
        $entityManager->flush();

        $jsonContent = $normalizer->normalize($event,'json',['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));

    }


    /**
     * @Route("/project/sms",name="sendSmsjson")
     */
    public function sendSMSJson()
    {
        $message = $this->twilio->messages->create(
            '+21623277171', // Send text to this number
            array(
                'from' => '+17814262448', // My Twilio phone number
                'body' => 'Hello from Awesome Massages. A reminder that your massage appointment is for today at '  . ' for any questions.'
            )
        );

        return new Response("SMS Sent");
        
    }

}