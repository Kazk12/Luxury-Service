<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Candidate;
use App\Entity\Experience;
use App\Entity\Gender;
use App\Entity\JobCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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

            ->add('shortDescription', TextType::class, [
                'label' => 'Short description for your profile, as well as more personnal informations (e.g. your hobbies/interests ). You can also paste any link you want.',
                'attr' => [
                    'id' => 'description',
                    'cols' => "50",
                    'rows' => "10",
                    'class' => 'materialize-textarea form-control'
                ],
            ])

            ->add('gender', EntityType::class, [
                'class' => Gender::class,
                'attr' => [
                    'id' => 'gender',
                    'class' => 'form-control ',
                ],
                'label' => "Gender :",
                'label_attr' => [
                    'class' => 'active',  
                ]
            ])


            ->add('experience', EntityType::class, [
                'class' => Experience::class,
                'attr' => [
                    'id' => 'experience',
                    'class' => 'form-control ',
                ],
                'label' => "Gender :",
                'label_attr' => [
                    'class' => 'active',  
                ]
            ])

            ->add('jobCategory', EntityType::class, [
                'class' => JobCategory::class,
                'attr' => [
                    'id' => 'job_sector',
                    'class' => 'form-control ',
                    'data-placeholder' => 'Type in or Select job sector you would be interested in.',
                ],
                'label' => "Interest in job sector :",
                'label_attr' => [
                    'class' => 'active',  
                ]
            ])

            ->add('thumbnailFile', FileType::class, [
                
                'required' => false,
                'attr' => [
                    'id' => 'profile_picture',
                    'class' => 'form-control',
                    'size' => '20000000',
                    'accept' => '.pdf,.jpg,.doc,.docx,.png,.gif',

                ],
            ])
            ->add('cvFile', FileType::class, [
                
                'required' => false,
                'attr' => [
                    'id' => 'cv',
                    'class' => 'form-control',
                    'size' => '20000000',
                    'accept' => '.pdf,.jpg,.doc,.docx,.png,.gif',

                ],
            ])

            ->add('passportFile', FileType::class, [
                
                'required' => false,
                'attr' => [
                    'id' => 'passport',
                    'class' => 'form-control',
                    'size' => '20000000',
                    'accept' => '.pdf,.jpg,.doc,.docx,.png,.gif',

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
