<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Evaluation;
use App\Entity\Formation;
use App\Form\EvaluationType;
use App\Form\FormationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class FormationController extends AbstractController
{
    /**
     * @Route("/formation", name="formation")
     */
    public function index(Request $request): Response
    {
        $em=$request->query->get('test');


        return $this->render('baseBack.html.twig', [
            'controller_name' => 'FormationController',
            'ajout'=>$em

        ]);
    }
    /**
     * @Route("/formationonglet", name="formationonglet")
     */
    public function indexformation(Request $request): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Formation::class)->getformationwithreviews();
        $choixselected=$request->request->get('selectcateg');
        $result2=$em->getRepository(Categorie::class)->findAll();
        if($choixselected=="default")
        {
            $result=$em->getRepository(Formation::class)->getformationwithreviews();
        }
      else if($choixselected!="reviews" && $choixselected!="all")
      {
          $result=$em->getRepository(Formation::class)->Filtrage($choixselected);
      }
      else if($choixselected=="reviews") {
          $result=$em->getRepository(Formation::class)->Filtrage_reviewsdesc();
      }
      else if($choixselected=="all")
      {
          $result=$em->getRepository(Formation::class)->getformationwithreviews();
      }





        return $this->render('/Formation/formationonglet.html.twig', [
            'controller_name' => 'FormationController',
          'result'=>$result,
            'result2'=>$result2


        ]);
    }
    /**
     * @Route("/formationfront", name="formationfront")
     */
    public function index2(): Response
    {       $em=$this->getDoctrine()->getManager();
            $result=$em->getRepository(Formation::class)->findAll();
            $al=$em->getRepository(Formation::class)->getformationeval();
        return $this->render('baseformation.html.twig', [
            'controller_name' => 'FormationController',
            'result'=>$result,
            'evaluation'=>$al
        ]);
    }
    /**
     * @Route("/cat85a2d8d", name="searchformation")
     */
    public function search(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $input = $request->get('search');

        $tab = $em->getRepository(Formation::class)->search($input);
        return $this->render('Formation/formationonglet.html.twig', [

            'result' => $tab,
        ]);
    }

    /**
     * @Route("/formationdetails/{id}", name="formationdetails")
     */
    public function showformationdetails(int $id,Request $request): Response

    {
        $Evaluation=new Evaluation();
        $form = $this->createForm(EvaluationType::class, $Evaluation);
        $form->handleRequest($request);
        //$choix=$request->request->get('selectcateg');
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Formation::class)->find($id);
        $evaluation_formation=$em->getRepository(Evaluation::class)->findBy(['idFormation'=>$result]);


        if ($form->isSubmitted() && $form->isValid()) {
            $note=$request->request->get('note');
            $Evaluation->setIdFormation($result);


            $Evaluation->setNote($note);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Evaluation);
            $entityManager->flush();
            $test=1;


            return $this->redirectToRoute('formationfront',
                [
                    'test'=>$test
                ]);
        }

        return $this->render('/Formation/formationdetails.html.twig', [
            'controller_name' => 'FormationController',
            'result'=>$result,
            'form' => $form->createView(),
            'evaluation'=>$evaluation_formation
        ]);
    }
    /**
     * @Route("/allformations", name="allformations")
     */
    public function showformations(): Response
    {
        $em=$this->getDoctrine()->getManager();


        $result=$em->getRepository(Formation::class)->findBy(['id_formateur'=>$this->getUser()->getId()]);


        return $this->render('/Formation/allformations.html.twig', [
            'controller_name' => 'FormationController',
            'result'=>$result
        ]);
    }
    /**
     * @Route("/deleteformation/{id}", name="delete")
     */
    public function delete(int $id ): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result2=$em->getRepository(Formation::class)->findBy(['id_formateur'=>1]);
        $result=$em->getRepository(Formation::class)->find($id);
        $em->remove($result);
        $em->flush();
        return $this->redirect($this->generateUrl("allformations"));
    }

    /**
     * @Route("/readmore/{id}", name="readmore")
     */
    public function readmore(int $id ): Response
    {
        $em=$this->getDoctrine()->getManager();
       // $result2=$em->getRepository(Formation::class)->findBy(['id_formateur'=>1]);
        $result=$em->getRepository(Formation::class)->find($id);

        return $this->render('/Formation/readmore.html.twig', [
            'controller_name' => 'FormationController',
            'result'=>$result->getPath()
        ]);
    }
    /**
     * @Route("/newformation", name="formation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        $formation->setidformateur($this->getUser()->getId());
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Categorie::class)->findAll();
        $choix=$request->request->get('selectcateg');

        

        if ($form->isSubmitted() && $form->isValid()) {
            $file=$request->files->get('formation')['path'];
            $upload=$this->getParameter('uploads_directory');
            $filename=md5(uniqid())  . '.'  . $file->guessExtension();
            $file->move(
                $upload,
                $filename
            );
            $choix=$request->request->get('selectcateg');

            $entityManager = $this->getDoctrine()->getManager();

            $formation->setPath($filename);
            $formation->setCategorie($choix);

            $entityManager->persist($formation);
            $entityManager->flush();
            $test=1;

            return $this->redirectToRoute('allformations',
            [
                'test'=>$test
            ]);
        }

        return $this->render('Formation/formulaire.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
            'result'=>$result,
            'choix'=>$choix
        ]);
    }
    /**
     * @Route("/{id}/modifier", name="Modifier")
     */
    public function edit(Request $request, Formation $formation): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Categorie::class)->findAll();
        $choix=$request->request->get('selectcateg');

        if ($form->isSubmitted() && $form->isValid()) {
            $choix=$request->request->get('selectcateg');
            $formation->setCategorie($choix);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('allformations');
        }

        return $this->render('Formation/edit.html.twig', [
            'formation' => $formation,
            'result'=>$result,
            'form' => $form->createView(),
            'choix'=>$choix
        ]);
    }
    /**
     * @Route("/create-checkout-session", name="checkout")
     */
    public function checkout(): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_qZtPCIcjq5ZOuw6O3wLQMSk400TjP4NGwz');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'T-shirt',

                    ],
                    'unit_amount' => 2000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
        ]);
        return new JsonResponse([ 'id' => $session->id ]);

    }




}
