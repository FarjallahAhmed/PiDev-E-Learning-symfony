<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objet')
            ->add('type')
            ->add('objectif',TextareaType::class)
            ->add('nbParticipants')
            ->add('coutHj')
            ->add('nbJour')
            ->add('coutFin')
            ->add('dateReelle',DateType::class,[
                'data' => new \DateTime(),
                'widget' => 'single_text',
            ])
            ->add('datePrevu',DateType::class,[
                'data' => new \DateTime(),
                'widget' => 'single_text',
            ])
            ->add('path',FileType::class,[
                'mapped'=>false,


            ])
            ->add("Submit",SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
