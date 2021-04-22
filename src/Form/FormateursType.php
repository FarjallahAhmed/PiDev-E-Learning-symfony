<?php

namespace App\Form;

use App\Entity\Formateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class FormateursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('datenaissance',DateType::class,[
                'widget' => 'single_text',
                    'attr' => ['class' => 'js-datepicker'],
                    'html5' => true,
                ])
            
            ->add('cin')
            ->add('email', EmailType::class)
            ->add('login')
            ->add('pwd', PasswordType::class)
            ->add('num')

            ->add('specialite')
            ->add('justificatif', FileType::class)
            ->add('captcha', CaptchaType::class)
      
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formateurs::class,
        ]);
    }
}
