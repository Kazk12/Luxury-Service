<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Candidate;
use App\Entity\Experience;
use App\Entity\Gender;
use App\Entity\JobCategory;
use DateTimeImmutable;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First name',
                'required' => false,
                'attr' => [
                    'id' => 'first_name',
                    'class' => 'form-control'
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last name',
                'attr' => [
                    'id' => 'last_name',
                    'class' => 'form-control'
                ],
            ])
            ->add('currentLocation', TextType::class, [
                'label' => 'Current location',
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
                ],
                'placeholder' => 'Choose an option...',
                
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

            ->add('email', EmailType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Email',
                'attr' => [
                    'id' => 'email',
                    'class' => 'form-control',
                ],
            ])
            ->add('newPassword', RepeatedType::class, [
                'mapped' => false,
                'required' => false,
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'New Password',
                    'attr' => [
                        'class' => 'form-control',
                        'id' => 'password',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirm New Password',
                    'attr' => [
                        'class' => 'form-control',
                        'id' => 'password_repeat',
                    ],
                ],
                'invalid_message' => 'The password fields must match.',
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('dateBirth', DateType::class, [
                'label' => 'Birthdate',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'birth_date',
                ],
            ])

            ->addEventListener(FormEvents::POST_SUBMIT, $this->setUpdatedAt(...))


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

    private function setUpdatedAt(FormEvent $event) : void
    {
        $candidate = $event->getData();
        $candidate->setUpdatedAt(new DateTimeImmutable());
    }
}
