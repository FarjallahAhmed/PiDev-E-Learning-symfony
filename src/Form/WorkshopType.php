<?php

namespace App\Form;

use App\Entity\Workshop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class WorkshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('namecalendar')
            ->add('nomevent', TextType::class, [
                'help' => 'Choose Event Name'
            ])
            ->add('datedebut',DateType::class,[
                'data' => new \DateTime(),
                'widget' => 'single_text',
            ])
            ->add('datefin',DateType::class,[
                'data' => new \DateTime(),
                'widget' => 'single_text',
            ])
            ->add('hdebut',TimeType::class,[
                'widget' => 'single_text',
                ])
            ->add('hfin',TimeType::class,[
                'widget' => 'single_text',
            ])
            ->add('lieu',TextType::class)
            ->add('nbparticipant',NumberType::class)
            ->add('type',ChoiceType::class, [
                'placeholder' => 'Choose a type',
                'choices' => [
                    'Team building' => 'Team building',
                    'Soft Skills' => 'Soft Skills',
                    'Conference' => 'Conference',
                    'Seminaire' => 'Seminaire',
                ],
                'required' => false,
            ])
            ->add('description',TextareaType::class)
            ->add('prix',NumberType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Workshop::class,
        ]);
    }
}
