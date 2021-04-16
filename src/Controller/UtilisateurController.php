<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participants;
use App\Entity\Formateurs;
use App\Form\ParticipantsType;
use App\Form\FormateursType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditParticipantType;
use App\Form\EditFormateurType;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class UtilisateurController extends AbstractController
{
    /**
     * @Route("/addparticipant", name="addparticipant")
     */
    public function index(Request $request): Response
    {
        $participant = new Participants();
        $form = $this->createForm(ParticipantsType::class, $participant);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);

        $participant->setCertificatsobtenus(0);
        $participant->setNombredeformation(0);


        if ($form->isSubmitted()&& $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($participant);
            $entityManager->flush();
            $message = "Success! You Can Access To You Account.";
            return $this->render('utilisateur/addParticipant.html.twig', [
                'controller_name' => 'UtilisateurController',
                'form' => $form->createView(),
                'message' => $message,
            ]);

        }

        $message ="";
        return $this->render('utilisateur/addParticipant.html.twig', [
            'controller_name' => 'UtilisateurController',
            'form' => $form->createView(),
            'message' => $message
        ]);
    }

    /**
     * @Route("viewparticipants", name="viewparticipants", methods={"GET"})
     */

    public function listBack(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Participants::class)->findAll();
        return $this->render('utilisateur/participantlistback.html.twig', [
            'participant' => $em->getRepository(Participants::class)->findAll(),
        ]);
    }


    /**
     * @Route("deleteParticipants/{id}", name="participants_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $participants = $em->getRepository(Participants::class)->find($id);
        $em->remove($participants);
        $em->flush();
        return $this->redirectToRoute('viewparticipants');
        
    }


    /**
     * @Route("profileparticipant", name="profileparticipant")
     */
    public function ProfileParticipant(): Response
    {

        return $this->render('utilisateur/profileParticipant.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);



    }

     /**
     * @Route("editparticipant/{id}", name="participant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Participants $participants): Response
    {


        $form = $this->createForm(EditParticipantType::class, $participants);
        $form->add('Modifier', SubmitType::class);
        $form->handleRequest($request);

       

        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var UploadedFile $uploadedFile */

           $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile)
            {
           $fileName = $uploadedFile->getClientOriginalName();
           // moves the file to the directory where brochures are stored
           
           $uploadedFile->move($this->getParameter('pictures_directory'), $fileName);
           
            $participants->setImage($uploadedFile->getClientOriginalName());
            }
            $this->getDoctrine()->getManager()->flush();

            $message = "Success! Informations Changed.";
            return $this->render('utilisateur/profileParticipant.html.twig', [
                'controller_name' => 'UtilisateurController',
                'participants' => $participants,
                'form' => $form->createView(),
                'message' => $message,
            ]);

        }
       $message = "";
    
        return $this->render('utilisateur/profileParticipant.html.twig', [
            'participants' => $participants,
            'form' => $form->createView(),
            'message' => $message
          
        ]);
    }

    /**
     * @Route("/addformateur", name="addformateur")
     */
    public function addformateur(Request $request): Response
    {
        $formateur = new Formateurs();
        $form = $this->createForm(FormateursType::class, $formateur);
        $form->add('Submit', SubmitType::class);
        $form->handleRequest($request);

     


        if ($form->isSubmitted() && $form->isValid() ){

      
            
                /** @var UploadedFile $uploadedFile */
    
               $uploadedFile = $form['justificatif']->getData();
                if ($uploadedFile)
                {
               $fileName = $uploadedFile->getClientOriginalName();
               // moves the file to the directory where brochures are stored
               
               $uploadedFile->move($this->getParameter('pictures_directory'), $fileName);
               
                $formateur->setJustificatif($uploadedFile->getClientOriginalName());
                }


                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($formateur);
                    $entityManager->flush();
                    $message = "Success! You Can Access To You Account.";
                     return $this->render('utilisateur/addformateur.html.twig', [
                            'controller_name' => 'UtilisateurController',
                            'form' => $form->createView(),
                            'message' => $message,
                        ]);
        }

        $message ="";
        return $this->render('utilisateur/addformateur.html.twig', [
                            'controller_name' => 'addformateur.html.twig',
                            'form' => $form->createView(),
                            'message' => $message
        ]);
    }


    /**
     * @Route("viewformateurs", name="viewformateurs", methods={"GET"})
     */

    public function listBackFormateur(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Formateurs::class)->findAll();
       
        return $this->render('utilisateur/formateurlistback.html.twig', [
            'formateur' => $em->getRepository(Formateurs::class)->findAll(),
            
        ]);
    }



    /**
     * @Route("activerformateur/{id}", name="activerformateur", methods={"GET","POST"})
     */
    public function ActiverFormateur(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $formateurs = $em->getRepository(Formateurs::class)->find($id);       
        $formateurs->setEtat(1);  
        $em->flush();  
        
        return $this->redirectToRoute('viewformateurs');
    }


     /**
     * @Route("deleteFormateurs/{id}", name="formateurs_delete")
     */
    public function deleteFormateur($id)
    {
        $em = $this->getDoctrine()->getManager();
        $formateur = $em->getRepository(Formateurs::class)->find($id);
        $em->remove($formateur);
        $em->flush();
        return $this->redirectToRoute('viewformateurs');
    }


    /**
     * @Route("profileformateur", name="profileformateur")
     */
    public function ProfileFormateur(): Response
    {

        return $this->render('utilisateur/profileFormateur.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);



    }




    /**
     * @Route("editformateur/{id}", name="editformateur", methods={"GET","POST"})
     */
    public function editFormateurProfile(Request $request, Formateurs $formateur): Response
    {


        $form = $this->createForm(EditFormateurType::class, $formateur);
        $form->add('Submit', SubmitType::class);
        $form->handleRequest($request);

       

        if ($form->isSubmitted() && $form->isValid()) {

             /** @var UploadedFile $uploadedFile */

           $uploadedFile = $form['imageFile']->getData();
           if ($uploadedFile)
           {
          $fileName = $uploadedFile->getClientOriginalName();
          // moves the file to the directory where brochures are stored
          
          $uploadedFile->move($this->getParameter('pictures_directory'), $fileName);
          
           $formateur->setImage($uploadedFile->getClientOriginalName());
           }
           $this->getDoctrine()->getManager()->flush();

           $message = "Success! Informations Changed.";
           return $this->render('utilisateur/profileFormateur.html.twig', [
               'controller_name' => 'UtilisateurController',
               'formateur' => $formateur,
               'form' => $form->createView(),
               'message' => $message,
           ]);
            
         

        }
            $message ="";
        return $this->render('utilisateur/profileFormateur.html.twig', [
            'message' => $message,
            'formateur' => $formateur,
            'form' => $form->createView(),
          
        ]);
    }



}
