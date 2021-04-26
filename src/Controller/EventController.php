<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Workshop;
use App\Form\CommentType;
use App\Services\QrcodeService;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;

use App\Form\WorkshopType;
use App\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use \Twilio\Rest\Client;
class EventController extends AbstractController
{
    private $twilio;

    public function __construct(Client $twilio) {
       $this->twilio = $twilio;

     }
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
            $this->addFlash('success', 'Event Ajouter avec sucess!');
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
    public function showAllEvent(WorkshopRepository $calendar,Request $request)
    {
        $search = $request->query->get("search");
        $events = $calendar->findAllWithSearch($search);



        return $this->render('event/showEvent.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/event/pagination", name="pagination")
     */
    public function pagination(WorkshopRepository $calendar, PaginatorInterface $paginator,Request $request)
    {
        //$search = $request->query->get("search");
        $eventall = $calendar->pagination();

        $events = $paginator->paginate(
        // Doctrine Query, not results
            $eventall,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );


        return $this->render('event/showEventFront.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/event/showEventFront", name="showEventFront")
     */
    public function showAllEventFront(WorkshopRepository $calendar,Request $request,PaginatorInterface $paginator)
    {
        //$events = $calendar->findAll();

        $filters = $request->get("type");



        //dd($qrCode);

        // On récupère les annonces de la page en fonction du filtre

        // On récupère le nombre total d'annonces
        //$events = $calendar->getTotalEvent($filters);

        $event = $calendar->pagination($filters);

        $events = $paginator->paginate(
        // Doctrine Query, not results
            $event,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );




        //dd($events);
        // On vérifie si on a une requête Ajax
        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('event/_contentFront.html.twig', compact('events'))
            ]);
        }

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
            $this->addFlash('success', 'Event Ajouter avec sucess!');

            $message = $this->twilio->messages->create(
                '+21623277171', // Send text to this number
                array(
                    'from' => '+17814262448', // My Twilio phone number
                    'body' => 'Hello from High Rises. A new Event is Here  '.$workshop->getNomevent() .' we begin at '.$workshop->getDatedebut()->format('Y-M-D')  . ' for any questions visit our site web www.hightises.com .'
                )
            );
            $this->addFlash('success', $message);

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
            $this->addFlash('success', 'Edit avec sucess!');
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
     * @Route("/event/{id}/deleteEvent", name="deleteEv")
     */
    public function deleteEvent(Request $request,WorkshopRepository $repo,$id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $calendar = $repo->find($id);
        $entityManager->remove($calendar);
        $entityManager->flush();
        $this->addFlash('success', 'Delete avec sucess!');


        return $this->redirectToRoute('showEvent');
    }

    /**
     * @Route("/news/{id}/heart", name="event_heart", methods={"POST"})
     */
    public function toggleArticleHeart(Workshop $event,EntityManagerInterface $em)
    {
        $event->setHearts($event->getHearts() + 1);
        $em->flush();
        return new JsonResponse(['hearts' => $event->getHearts()]);
    }


    /**
     * @Route("/event/{id}/showDetailsEventFront", name="showDetailsEventFront")
     */
    public function detailsEvent(WorkshopRepository $calendar,$id,Request $request, QrcodeService $qrcodeService)
    {
        $event = $calendar->find($id);
        $qrCode[] = null;

        $qrCode = $qrcodeService->qrcode($event->getNomevent());
        $comment = new Comment();

        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $comment->setWorkshop($event);
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('showDetailsEventFront',['id' => $id]);
        }


        return $this->render('event/detailsEventFront.html.twig', [
            'event' => $event,
            'formComment' => $form->createView(),
            'qrCode' => $qrCode,

        ]);
    }

    /**
     * @Route("/event/stats",name="statsEvent")
     */
    public function stats(WorkshopRepository $repo){

        $events = $repo->findAll();
        $eventByDate = $repo->countByDate();

        $eventType = [];
        $eventCount = [];
        $eventCountHearts = [];
        $eventColor = [];

        foreach ($eventByDate as $event){
            //$eventType[] = $event->getType();
            $eventCount[] = $event['count'];
           if ($event['type'] == 'Soft Skills')
               $eventColor[] = '#933EC5' ;
             elseif ($event['type'] == 'Team building')
             $eventColor[] = '#FF5722';
             elseif ($event['type'] == 'Conference')
             $eventColor[] = '#f3c30b';
            elseif ($event['type'] == 'Seminaire')
            $eventColor[] = '#00BCD4';

        }



        $eventByLike = $repo->countByLike();
        foreach ($eventByLike as $event){
            $eventType[] = $event['typeW'];
            $eventCountHearts[] = $event['count'];
        }


        return $this->render('event/stats.html.twig',[
            'eventType' => json_encode($eventType),
            'eventCount' => json_encode($eventCount),
            'eventColor' => json_encode($eventColor),
            'like' => json_encode($eventCountHearts),
        ]);

    }


    /**
     * @Route("/event/sms",name="sendSms")
     */
    public function sendSMS()
    {
        $message = $this->twilio->messages->create(
            '+21623277171', // Send text to this number
            array(
                'from' => '+17814262448', // My Twilio phone number
                'body' => 'Hello from Awesome Massages. A reminder that your massage appointment is for today at '  . ' for any questions.'
            )
        );
        $this->addFlash('success', $message);


        return $this->redirectToRoute('showEvent');
    }




}
