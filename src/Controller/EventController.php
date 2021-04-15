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
<<<<<<< HEAD
        return $this->render('base.html.twig', [
=======
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
            if ($event->getType() == "Team building")
                $event->setDescription("#FF5722");
            else if ($event->getType() == "Soft Skills")
                $event->setDescription("#933EC5");
            else if ($event->getType() == "Conference")
                $event->setDescription("#f3c30b");
            else
                $event->setDescription("#00BCD4");
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDatedebut()->format('Y-m-d H:i:s'),
                'end' => $event->getDatefin()->format('Y-m-d H:i:s'),
                'title' => $event->getNomevent(),
                'color' => $event->getDescription(),
                'type' => $event->getType(),
                'prix' => $event->getPrix(),
                'hfin' => $event->getHfin(),
                'hdebut' => $event->getHdebut(),
            ];
        }

        $data = json_encode($rdvs);
        return $this->render('event/index.html.twig', [
>>>>>>> ad3313ef7ac1f7ffe6f676fb546ae5809b48e9cd
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
     * @Route("/event/showEvent", name="showEvent")
     */
    public function showAllEvent(WorkshopRepository $calendar)
    {
        $events = $calendar->findAll();
        return $this->render('event/showEvent.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/event/showEventFront", name="showEventFront")
     */
    public function showAllEventFront(WorkshopRepository $calendar)
    {
        $events = $calendar->findAll();

        return $this->render('event/showEventFront.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/event/newEvent", name="newEvent")
     */
    public function newEvent(Request $request): Response
    {
        $workshop = new Workshop();
        $form = $this->createForm(WorkshopType::class,$workshop);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($workshop);
            $em->flush();
            return $this->redirectToRoute('showEvent');
        }
        return $this->render('event/addEvent.html.twig', [
            'formEvent' => $form->createView(),
        ]);
    }
    /**
     * @Route("/event/{id}/editEvent", name="edit")
     */
    public function edit(Request $request,WorkshopRepository $repo,$id): Response
    {
        $workshop = $repo->find($id);
        $form = $this->createForm(WorkshopType::class,$workshop);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($workshop);
            $em->flush();
            return $this->redirectToRoute('showEvent');
        }
        return $this->render('event/addEvent.html.twig', [
            'formEvent' => $form->createView(),

        ]);
    }
    /**
     * @Route("/event/{id}/edit", name="eventEdit", methods={"PUT"})
     */
    public function editEvent(?Workshop $calendar, Request $request)
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


        return $this->render('event/index.html.twig', [

        ]);
    }
    /**
     * @Route("/event/{id}/delete", name="deleteEvent", methods={"DELETE"})
     */
    public function delete(Request $request,WorkshopRepository $repo): Response
    {
            $donnees = json_decode($request->getContent());
            $entityManager = $this->getDoctrine()->getManager();
            $calendar = $repo->find($donnees->id);
            $entityManager->remove($calendar);
            $entityManager->flush();


        return $this->redirectToRoute('event');
    }
    /**
     * @Route("/event/{id}/deleteEvent", name="delete")
     */
    public function deleteEvent(Request $request,WorkshopRepository $repo,$id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $calendar = $repo->find($id);
        $entityManager->remove($calendar);
        $entityManager->flush();


        return $this->redirectToRoute('showEvent');
    }
}
