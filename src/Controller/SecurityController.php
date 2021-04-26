<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Formateurs;
use App\Entity\Participants;

class SecurityController extends AbstractController
{
    public static $typeUser;
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {

            
            
            $em=$this->getDoctrine()->getManager();
            $typeUser = $em->getClassMetadata(get_class($this->getUser()))->discriminatorValue;

            if ($typeUser == "participants")
            {  
                $emCheck=$this->getDoctrine()->getManager();
                $participants = $emCheck->getRepository(Participants::class)->find($this->getUser()->getId());
                            if ($participants->getCertificatsobtenus()==1)
                                {
                                    return $this->redirectToRoute('app_logout'); 
                                }
                                else
                return $this->redirectToRoute('home');
            }
             else 
                if ($typeUser == "utilisateurs")
                {
                    return $this->redirectToRoute('viewformateurs');
                }
                    else 
                        if ($typeUser == "formateurs")
                        {
                            $emCheck=$this->getDoctrine()->getManager();
                            $formateurs = $emCheck->getRepository(Formateurs::class)->find($this->getUser()->getId());
                            if ($formateurs->getEtat()==0)
                                {
                                 
                                    return $this->redirectToRoute('app_logout'); 
                                }
                                else
                                    return $this->redirectToRoute('homeformateur');   
                        }   
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
       
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        $this->addFlash('success', 'Article Created! Knowledge is power!');
    }
}
