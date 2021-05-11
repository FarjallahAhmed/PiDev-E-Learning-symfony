<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participants;
use App\Entity\Formateurs;
use App\Entity\Utilisateurs;

use App\Form\ParticipantsType;
use App\Form\FormateursType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditParticipantType;
use App\Form\EditFormateurType;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\ParticipantsRepository;
use App\Repository\FormateursRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class UserApiController extends AbstractController
{

    // API MOBILE 



                                        // ------- AFFICHER UTILISATEURS ---------

     /**
     * @Route("/getParticipantsJSON", name="getparticipantsjson")
     */
    public function getParticipantsJson(ParticipantsRepository $repo ,NormalizerInterface $Normalizer)
    {
        $participants = $repo->findAll();
        $json = $Normalizer->normalize($participants,'json',['groups' => 'post:read']);
       
        return new Response(json_encode($json));

        
        
        
    }

    /**
     * @Route("/getParticipantsByIdJSON/{id}", name="getparticipantsbyidjson")
     */
    public function getParticipantsByIdJson(ParticipantsRepository $repo ,NormalizerInterface $Normalizer,$id)
    {
        $participants = $repo->find($id);
        $json = $Normalizer->normalize($participants,'json',['groups' => 'post:read']);
       
        return new Response(json_encode($json));

        
        
        
    }


    /**
     * @Route("/getFormateursJSON", name="getformateursjson")
     */
    public function getFormateursJson(FormateursRepository $repo ,NormalizerInterface $Normalizer)
    {
        $formateurs = $repo->findAll();
        $json = $Normalizer->normalize($formateurs,'json',['groups' => 'post:read']);
        return new Response(json_encode($json));
        
    }

    /**
     * @Route("/getFormateursByIdJSON/{id}", name="getformateursbyidjson")
     */
    public function getFormateursByIdJson(FormateursRepository $repo ,NormalizerInterface $Normalizer,$id)
    {
        $formateurs = $repo->find($id);
        $json = $Normalizer->normalize($formateurs,'json',['groups' => 'post:read']);
        return new Response(json_encode($json));
        
    }




                                             // ------- Ajouter UTILISATEURS ---------
    /**
     * @Route("/addParticipantsJSON", name="addparticipantsjson")
     */
    public function addParticipantApi(Request $request, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $participant = new Participants();


        $participant->setNom($request->get('nom'));
        $participant->setPrenom($request->get('prenom'));
       // $participant->setDatenaissance(date_create_from_format("format_string", $request->get('date')));
        //$participant->setDatenaissance($request->get('date'));
        
        $participant->setCin($request->get('cin'));
        $participant->setEmail($request->get('email'));
        $participant->setLogin($request->get('login'));
        $participant->setPassword($request->get('pwd'));
        $participant->setNum($request->get('num'));

        $participant->setCertificatsobtenus($request->get('cero'));
        $participant->setInteressepar($request->get('about'));
        $participant->setNiveauetude($request->get('niveau'));
        $participant->setNombredeformation($request->get('nbfor'));
        $em->persist($participant);
        $em->flush();
        $jsonContent = $Normalizer->normalize($participant,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
        
        
  
    }

    /**
     * @Route("/addFormateursJSON", name="addformateursjson")
     */
    public function addFormateurApi(Request $request, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $formateur = new Formateurs();


        $formateur->setNom($request->get('nom'));
        $formateur->setPrenom($request->get('prenom'));
       // $formateur->setDatenaissance(date_create_from_format("format_string", $request->get('date')));
        //$formateur->setDatenaissance($request->get('date'));
        
        $formateur->setCin($request->get('cin'));
        $formateur->setEmail($request->get('email'));
        $formateur->setLogin($request->get('login'));
        $formateur->setPassword($request->get('pwd'));
        $formateur->setNum($request->get('num'));

        $formateur->setSpecialite($request->get('spec'));
        $formateur->setJustificatif($request->get('just'));
        $formateur->setEtat($request->get('etat'));

        $em->persist($formateur);
        $em->flush();
        $jsonContent = $Normalizer->normalize($formateur,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
  
    }


                                                 // ------- Modifier UTILISATEURS ---------
     /**
     * @Route("/modifierParticipantsJSON/{id}", name="modifierparticipantsjson")
     */
    public function modifierParticipantsJson(Request $request,ParticipantsRepository $repo ,EntityManagerInterface $em,NormalizerInterface $Normalizer,$id)
    {

        $em = $this->getDoctrine()->getManager();

        $participants = $em->getRepository(Participants::class)->find($id);
        
        

        
        if ($request->get('email')!=NULL)
            $participants->setEmail($request->get('email'));

        if ($request->get('login')!=NULL)
            $participants->setLogin($request->get('login'));

        if ($request->get('pwd')!=NULL)
            $participants->setPassword($request->get('pwd'));

        if ($request->get('image')!=NULL)
            $participants->setImage($request->get('image'));
        
        if ($request->get('num')!=NULL)
            $participants->setNum($request->get('num'));

        if ($request->get('niveau')!=NULL)
            $participants->setNiveauetude($request->get('niveau'));

        if ($request->get('about')!=NULL)
            $participants->setInteressePar($request->get('about'));

        
        
        $em->flush();
        $jsonContent = $Normalizer->normalize($participants,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
        
    }              
    
    

    /**
     * @Route("/modifierFormateursJSON/{id}", name="modifierformateursjson")
     */
    public function modifierFormateursJson(Request $request,FormateursRepository $repo ,EntityManagerInterface $em, NormalizerInterface $Normalizer,$id)
    {
         
        $em = $this->getDoctrine()->getManager();

        $formateurs = $em->getRepository(Formateurs::class)->find($id);
       // dd($formateurs);
        
        
         if ($request->get('email')!=NULL)
              $formateurs->setEmail($request->get('email'));

        if ($request->get('login')!=NULL)
              $formateurs->setLogin($request->get('login'));

        if ($request->get('pwd')!=NULL)
              $formateurs->setPassword($request->get('pwd'));

        if ($request->get('image')!=NULL)
              $formateurs->setImage($request->get('image'));
        
        if ($request->get('num')!=NULL)
              $formateurs->setNum($request->get('num'));


        if ($request->get('spec')!=NULL)
            $formateurs->setSpecialite($request->get('spec'));

        
            $em->flush();
            $jsonContent = $Normalizer->normalize($formateurs,'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));
        
    }   
    
    


                                    // ------- Supprimer UTILISATEURS ---------

     /**
     * @Route("/deleteParticipantsJSON/{id}", name="deleteparticipantsjson")
     */
    public function deleteParticipantsJson(Request $request,ParticipantsRepository $repo ,EntityManagerInterface $em, SerializerInterface $serializerInterface,$id)
    {
        $participants = $repo->find($id);
       
        $em->remove($participants);
        $em->flush();
        $json = $serializerInterface->serialize($participants,'json',['groups' => 'post:read']);
       
        return new Response("Participant Deleted");
        
    }       
    
    
    /**
     * @Route("/deleteFormateursJSON/{id}", name="deleteformateursjson")
     */
    public function deleteFormateursJson(Request $request,FormateursRepository $repo ,EntityManagerInterface $em, SerializerInterface $serializerInterface,$id)
    {
        $formateurs = $repo->find($id);
       
        $em->remove($formateurs);
        $em->flush();
        $json = $serializerInterface->serialize($formateurs ,'json',['groups' => 'post:read']);
       
        return new Response("Formateurs Deleted");
        
    }  



                                    // DESACTIVER ACCOUNT 

    /**
     * @Route("/desactiverFormateursJSON/{id}", name="desactiverformateursjson")
     */
    public function desactiverFormateursJson(Request $request,FormateursRepository $repo ,EntityManagerInterface $em, NormalizerInterface $Normalizer,$id)
    {
         
        $em = $this->getDoctrine()->getManager();

        $formateurs = $em->getRepository(Formateurs::class)->find($id);
       // dd($formateurs);

       $formateurs->setEtat(false);
        
            $em->flush();
            $jsonContent = $Normalizer->normalize($formateurs,'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));
        
    }   


    /**
     * @Route("/verifierUserJSON/{email}", name="verifieruserjson")
     */
    public function verifierUserJson(Request $request,FormateursRepository $repo ,EntityManagerInterface $em, NormalizerInterface $Normalizer,$email)
    {
         
        $em = $this->getDoctrine()->getManager();

        $utilisateurs = $em->getRepository(Utilisateurs::class)->findByEmail($email);
       // dd($formateurs);
            
            $em->flush();
            $jsonContent = $Normalizer->normalize($utilisateurs,'json',['groups'=>'post:read']);
            if ($jsonContent!=null)
                return new Response(json_encode($jsonContent));
            else
                return new Response(json_encode(null));

                

        
    }   


}