<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tab = $em->getRepository(Categorie::class)->findAll();
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categorie' => $tab,
        ]);
    }

    /**
     * @Route("/categorie/ajouter", name="ajouterCategorie")
     */
    public function ajouter(Request $request): Response
    {
        if($request->isMethod("post")) {
            $em = $this->getDoctrine()->getManager();

            $cat = new Categorie();
            $cat->setNom($request->get('nom'));
            $cat->setType($request->get('type'));
            $cat->setDescription($request->get('description'));

            $em->persist($cat);
            $em->flush();

            return $this->redirectToRoute('categorie');
        }
        return $this->render('categorie/ajouter.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    /**
     * @Route("/categorie/modifier/{id}", name="modifierCategorie")
     */
    public function modifier($id, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $cat = $em->getRepository(Categorie::class)->find($id);
        if($request->isMethod("post")) {

            $cat->setNom($request->get('nom'));
            $cat->setType($request->get('type'));
            $cat->setDescription($request->get('description'));

            $em->persist($cat);
            $em->flush();

            return $this->redirectToRoute('categorie');
        }
        return $this->render('categorie/modifier.html.twig', [
            'controller_name' => 'CategorieController',
            'cat' => $cat,
        ]);
    }

    /**
     * @Route("/categorie/supprimer/{id}", name="supprimerCategorie")
     */
    public function supprimer($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $cat = $em->getRepository(Categorie::class)->find($id);

        $em->remove($cat);
        $em->flush();

        return $this->redirectToRoute('categorie');
    }

    /**
     * @Route("/cat85a2d8d", name="searchCategorie")
     */
    public function search(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $input = $request->get('search');
        $tab = $em->getRepository(Categorie::class)->search($input);
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categorie' => $tab,
        ]);
    }

    /**
     * @Route("/testTMP", name="testTMP")
     */
    public function testTMP(): Response
    {
        return $this->render('categorie/testTMP.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }
}
