<?php

namespace App\Controller;

use App\Entity\Workshop;
use DateTime;

use App\Form\WorkshopType;
use App\Repository\WorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index(Request $request,WorkshopRepository $calendar): Response
    {
        $workshop = new Workshop();
        $form = $this->createForm(WorkshopType::class,$workshop);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($workshop);
            $em->flush();
            return $this->redirectToRoute('event');
        }
        $events = $calendar->findAll();
        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDatedebut()->format('Y-m-d H:i:s'),
                'end' => $event->getDatefin()->format('Y-m-d H:i:s'),
                'title' => $event->getNomevent(),
                'description' => $event->getDescription(),
                'type' => $event->getType(),
                'prix' => $event->getPrix(),
                'hfin' => $event->getHfin(),
                'hdebut' => $event->getHdebut(),
            ];
        }

        $data = json_encode($rdvs);
        return $this->render('event/panier.html.twig', [
            'controller_name' => 'EventController',
            'formEvent' => $form->createView(),
            'data'=> $data,

        ]);
    }
    /**
     * @Route("/addEvent", name="addEvent")
     */
    public function addEvent(Request $request): Response
    {
        $workshop = new Workshop();
        $form = $this->createForm(WorkshopType::class,$workshop);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($workshop);
            $em->flush();
            return $this->redirectToRoute('event');
        }
        return $this->render('event/add.html.twig', [
            'formEvent' => $form->createView(),
        ]);
    }
    /**
     * @Route("/", name="showEvent")
     */
    public function showAllEvent(WorkshopRepository $calendar)
    {
        $events = $calendar->findAll();
        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDatedebut()->format('Y-m-d H:i:s'),
                'end' => $event->getDatefin()->format('Y-m-d H:i:s'),
                'title' => $event->getNomevent(),
                'description' => $event->getDescription(),
                'type' => $event->getType(),
                'prix' => $event->getPrix(),
                'hfin' => $event->getHfin(),
                'hdebut' => $event->getHdebut(),
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('event/panier.html.twig', compact('data'));
    }
    /**
     * @Route("/event/{id}/edit", name="eventEdit", methods={"PUT"})
     */
    public function majEvent(?Workshop $calendar, Request $request)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start)
        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On vérifie si l'id existe
            if(!$calendar){
                // On instancie un rendez-vous
                $calendar = new Workshop();

                // On change le code
                $code = 201;
            }

            // On hydrate l'objet avec les données
            $calendar->setNomevent($donnees->title);

            $calendar->setDatedebut(new DateTime($donnees->start));
            if($donnees->allDay){
                $calendar->setDatefin(new DateTime($donnees->start));
            }else{
                $calendar->setDatefin(new DateTime($donnees->end));
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }


        return $this->render('event/panier.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
    /**
     * @Route("/event/{id}/delete", name="deleteEvent", methods={"DELETE"})
     */
    public function delete(Request $request, Workshop $calendar,WorkshopRepository $repo): Response
    {
            $donnees = json_decode($request->getContent());
            $entityManager = $this->getDoctrine()->getManager();
            $calendar = $repo->find($donnees->id);
            $entityManager->remove($calendar);
            $entityManager->flush();


        return $this->redirectToRoute('event');
    }
}
