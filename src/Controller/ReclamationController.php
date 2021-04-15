<?php

namespace App\Controller;

use App\Entity\Archive;
use App\Entity\Message;
use App\Entity\Reclamation;
use App\Entity\Utilisateurs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine();
        $tab = $em->getRepository(Reclamation::class)->findAll();
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
            'reclamation' => $tab,
        ]);
    }

    /**
     * @Route("/reclamation/ajouter", name="ajouterReclamation")
     */
    public function ajouter(Request $request): Response
    {
        if($request->isMethod("post")) {
            $em = $this->getDoctrine()->getManager();

            $rec = new Reclamation();
            $user = $em->getRepository(Utilisateurs::class)->find('43');
            $rec->setIdUser($user);
            $rec->setObjet($request->get('objet'));
            $msg = new Message();
            $msg->setContenu($request->get('message'));
            $rec->setIdMessage($msg);
            $rec->setDate(new \DateTime());

            $em->persist($msg);
            $em->persist($rec);
            $em->flush();

            return $this->redirectToRoute('reclamation');
        }
        return $this->render('reclamation/ajouter.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

    /**
     * @Route("/reclamation/modifier/{id}", name="modifierReclamation")
     */
    public function modifier($id, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $rec = $em->getRepository(Reclamation::class)->find($id);
        if($request->isMethod("post")) {

            $rec->setObjet($request->get('objet'));
            $rec->getIdMessage()->setContenu($request->get('message'));

            $em->persist($rec->getIdMessage());
            $em->persist($rec);
            $em->flush();

            return $this->redirectToRoute('reclamation');
        }
        return $this->render('reclamation/modifier.html.twig', [
            'controller_name' => 'ReclamationController',
            'rec' => $rec,
        ]);
    }

    /**
     * @Route("/reclamation/supprimer/{id}", name="supprimerReclamation")
     */
    public function supprimer($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $rec = $em->getRepository(Reclamation::class)->find($id);

        $arc = new Archive();
        $arc->setDate($rec->getDate());
        $arc->setIdMessage($rec->getIdMessage());
        $arc->setObjet($rec->getObjet());
        $arc->setIdUser($rec->getIdUser());

        $em->persist($arc);
        $em->remove($rec);
        $em->flush();

        return $this->redirectToRoute('reclamation');
    }

    /**
     * @Route("/reclamation/archive", name="archiveReclamation")
     */
    public function archive(): Response
    {
        $em = $this->getDoctrine();
        $tab = $em->getRepository(Archive::class)->findAll();
        return $this->render('reclamation/archive.html.twig', [
            'controller_name' => 'ReclamationController',
            'reclamation' => $tab,
        ]);
    }

    /**
     * @Route("/rec74ad5a8d4", name="searchReaclamation")
     */
    public function search(Request $request): Response
    {
        $em = $this->getDoctrine();
        $input = $request->get('search');
        $tab = $em->getRepository(Reclamation::class)->search($input);
        $users = $em->getRepository(Utilisateurs::class)->findAll();
        $msgs = $em->getRepository(Message::class)->findAll();
        return $this->render('reclamation/search.html.twig', [
            'controller_name' => 'ReclamationController',
            'reclamation' => $tab,
            'msgs' => $msgs,
            'users' => $users,
        ]);
    }
}
