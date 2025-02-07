<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Candidate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'firstName',
                'attr' => [
                    'id' => 'last_name',
                    'class' => 'form-control'
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'lastName',
                'attr' => [
                    'id' => 'last_name',
                    'class' => 'form-control'
                ],
            ])
            ->add('currentLocation', TextType::class, [
                'label' => 'currentLocation',
                'attr' => [
                    'id' => 'current_location',
                    'class' => 'form-control'
                ],
            ])
            ->add('adress', TextType::class, [
                'label' => 'adress',
                'attr' => [
                    'id' => 'address',
                    'class' => 'form-control'
                ],
            ])
            ->add('country', TextType::class, [
                'label' => 'country',
                'attr' => [
                    'id' => 'country',
                    'class' => 'form-control'
                ],
            ])
            ->add('nationality', TextType::class, [
                'label' => 'nationality',
                'attr' => [
                    'id' => 'nationality',
                    'class' => 'form-control'
                ],
            ])


            // ->add('date_naissance', DateTimeType::class, [
            //     'label' => 'Date_naissance',
            //     'attr' => [
            //         'id' => 'birth_date',
            //         'class' => 'form-control'
            //     ],
            // ])


            ->add('birthPlace', TextType::class, [
                'label' => 'birthPlace',
                'attr' => [
                    'id' => 'birth_place',
                    'class' => 'form-control'
                ],
            ]);
    }

   
        public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class, // Assure-toi que ça pointe bien vers l'entité Candidat
        ]);
    }
}
