<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActiviteController extends AbstractController
{
    /**
     * @Route("/activite", name="activite")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getRepository(Activite::class);

        $activites = $em->findAll();
        return $this->render('activite/index.html.twig', [
            'activites' => $activites,
        ]);
    }

    /**
     * @Route("/activite/{id}/deleteActivite", name="deleteActivite")
     */
    public function deleteEvent(Request $request,ActiviteRepository $repo,$id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $calendar = $repo->find($id);
        $entityManager->remove($calendar);
        $entityManager->flush();


        return $this->redirectToRoute('activite');
    }
    /**
     * @Route("/activite/newActivite", name="newActivite")
     */
    public function newEvent(Request $request): Response
    {
        $workshop = new Activite();
        $form = $this->createForm(ActiviteType::class,$workshop);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($workshop);
            $em->flush();
            return $this->redirectToRoute('activite');
        }
        return $this->render('activite/addActivite.html.twig', [
            'formActivite' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activite/{id}/editActivite", name="editActivite")
     */
    public function editActivite(Request $request,$id): Response
    {
        $workshop = new Activite();
        $em = $this->getDoctrine()->getRepository(Activite::class);
        $workshop = $em->find($id);
        $form = $this->createForm(ActiviteType::class,$workshop);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($workshop);
            $em->flush();
            return $this->redirectToRoute('activite');
        }
        return $this->render('activite/addActivite.html.twig', [
            'formActivite' => $form->createView(),
        ]);
    }
}
