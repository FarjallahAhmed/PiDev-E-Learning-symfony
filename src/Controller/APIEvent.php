<?php


namespace App\Controller;
use App\Entity\Comment;
use App\Entity\Workshop;
use App\Repository\WorkshopRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class APIEvent extends AbstractController
{
    /**
     * @Route("/APIEvent/AllEvent",name="AllEvent")
     */
    public function AllEvent(Request $request,NormalizerInterface $normalizer){

        $repo = $this->getDoctrine()->getRepository(Workshop::class);
        $events = $repo->findAll();




        $jsonContent = $normalizer->normalize($events,'json',['groups'=> 'post:read']);

        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/APIEvent/getEvent/{id}",name="getEvent")
     */
    public function EventId(Request $request,NormalizerInterface $normalizer,$id){

        $repo = $this->getDoctrine()->getRepository(Workshop::class);
        $events = $repo->find($id);


        //dd($comment);
        $jsonContent = $normalizer->normalize($events,'json',['groups'=> 'post:read']);

        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("APIEvent/addComment",name="APIaddComment")
     */
    public function APIAddComment(Request $request,NormalizerInterface $normalizer,WorkshopRepository $repository){

        $em = $this->getDoctrine()->getManager();
        $comment = new Comment() ;
        $comment->setAuthorName($request->get('authorName'));
        $comment->setContent($request->get('content'));
        $event = $repository->find($request->get('workshop')) ;

        $comment->setWorkshop($event);

        $em->persist($comment);
        $em->flush();
        $jsonContent = $normalizer->normalize($comment,'json',['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("APIEvent/addEvent",name="APIaddEvent")
     */
    public function APIAddEvent(Request $request,NormalizerInterface $normalizer){

        $em = $this->getDoctrine()->getManager();
        $event = new Workshop() ;
        $event->setNomevent($request->get('nomevent'));
        $event->setNamecalendar($request->get('namecalendar'));
        $event->setDatedebut(new \DateTime($request->get('datedebut')));
        $event->setDatefin(new \DateTime($request->get('datefin')));
        $event->setHdebut(new \DateTime($request->get('hdebut')));
        $event->setHfin(new \DateTime($request->get('hfin')));
        $event->setLieu($request->get('lieu'));
        $event->setNbparticipant($request->get('nbparticipant'));
        $event->setType($request->get('type'));
        $event->setDescription($request->get('description'));
        $event->setPrix($request->get('prix'));

        $em->persist($event);
        $em->flush();
        $jsonContent = $normalizer->normalize($event,'json',['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/APIEvent/editEvent/{id}",name="editEvent")
     */
    public function editEvent(Request $request,NormalizerInterface $normalizer,$id){

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Workshop::class)->find($id) ;
        $event->setNomevent($request->get('nomevent'));
        $event->setNamecalendar($request->get('namecalendar'));
        /*$event->setDatedebut($request->get('datedebut'));
        $event->setDatefin($request->get('datefin'));
        $event->setHdebut($request->get('hdebut'));
        $event->setHfin($request->get('hfin'));*/
        $event->setLieu($request->get('lieu'));
        $event->setNbparticipant($request->get('nbparticipant'));
        $event->setType($request->get('type'));
        $event->setDescription($request->get('description'));
        $event->setPrix($request->get('prix'));

        $em->persist($event);
        $em->flush();
        $jsonContent = $normalizer->normalize($event,'json',['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/APIEvent/deleteEvent/{id}",name="deleteEventAPI")
     */
    public function DeleteEvent(Request $request,NormalizerInterface $normalizer,$id){

        $entityManager = $this->getDoctrine()->getManager();
        $event = $entityManager->getRepository(Workshop::class)->find($id);
        $entityManager->remove($event);
        $entityManager->flush();

        $jsonContent = $normalizer->normalize($event,'json',['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/APIEvent/stat",name="statsEventAPI")
     */
    public function APIstat(WorkshopRepository $repo){

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

        return new JsonResponse([

            'eventType' => $eventType,
            'eventCount' => $eventCount,
            'like' => $eventCountHearts,


            ]);


    }

}