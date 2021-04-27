<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class ProjetController extends AbstractController
{
    /**
     * @Route("/projet", name="projet")
     */
    public function index(Request $request,ProjetRepository $repo): Response
    {

        $search = $request->query->get("search");
        $projets = $repo->findAllWithSearch($search);


        return $this->render('projet/index.html.twig', [
            'projets' => $projets,
        ]);
    }

    /**
     * @Route("/projet/tri", name="tri")
     */
    public function index1(Request $request,ProjetRepository $repo): Response
    {
        $search = $request->query->get("search");
        $projets = $repo->triedecroissant();
        if($search!=''){
        $projets = $repo->findAllWithSearch($search);}


        return $this->render('projet/trier.html.twig', [
            'projets' => $projets,
        ]);
    }

    /**
     * @Route("/projetFront", name="projetfront")
     */
    public function front(ProjetRepository $repos): Response
    {

        $date = new \DateTime('now');

        $notification = "";
        $events = $repos->findAll();

        foreach ($events as $event){
            $diff = date_diff($event->getDatec() ,$date);
            if ($diff->d<3){
                $notification .= $event->getNom() . "va terminer en " .(string)$diff->d;
            }


        }

        return $this->render('projet/projetFront.html.twig', [
            'events' => $events,
            'notification' => $notification
        ]);
    }

    /**
     * @Route("/projet/{id}/deleteProjet", name="deleteProjet")
     */
    public function deleteProjet(Request $request,ProjetRepository $repo,$id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $calendar = $repo->find($id);
        $entityManager->remove($calendar);
        $entityManager->flush();


        return $this->redirectToRoute('projet');
    }
    /**
     * @Route("/projet/newProjet", name="newProjet")
     */
    public function newEvent(Request $request): Response
    {
        $workshop = new Projet();
        $form = $this->createForm(ProjetType::class,$workshop);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($workshop);
            $em->flush();
            return $this->redirectToRoute('projet');
        }
        return $this->render('projet/addProjet.html.twig', [
            'formProjet' => $form->createView(),
        ]);
    }

    /**
     * @Route("/projet/{id}/editProjet", name="editProjet")
     */
    public function editActivite(Request $request,$id): Response
    {
        $workshop = new Projet();
        $em = $this->getDoctrine()->getRepository(Projet::class);
        $workshop = $em->find($id);
        $form = $this->createForm(ProjetType::class,$workshop);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($workshop);
            $em->flush();
            return $this->redirectToRoute('projet');
        }
        return $this->render('projet/addProjet.html.twig', [
            'formProjet' => $form->createView(),
        ]);
    }

    /**
     * @Route("/imprimerRes", name="imprimerRes")
     */
    function ImprimerRes(ProjetRepository $repo, Request $request){

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        $projet=$repo->findAll();


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView("projet/PDF.html.twig",
            ['projet'=>$projet]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);


    }
}
