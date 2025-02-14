<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CandidateType;
use App\Service\CandidateCompletionCalculator;
use App\Entity\User;
use App\Entity\Candidate;
use App\Repository\CandidateRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/candidate')]
final class CandidateController extends AbstractController
{
    #[Route('/profile', name: 'app_candidate_new', methods: ['GET', 'POST'])]
    public function index(
        CandidateRepository $candidatRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        CandidateCompletionCalculator $completionCalculator,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer
    ): Response
    {

        
        /** 
         * @var User $user
         */
        $user = $this->getUser();

        if ($user && $user->isVerified() == false) {
            return $this->render('security/notVerified.html.twig', [
                'errorVerified' => 'Veuillez confirmer votre email depuis votre adresse mail !',
            ]);
        } else {
            $candidat = $candidatRepository->findOneBy(['user' => $user->getId()]);
            if ($candidat === null) {
                $candidat = new Candidate();
                $candidat->setUser($user);

            }

            $form = $this->createForm(CandidateType::class, $candidat);
            $form->handleRequest($request);




            if ($form->isSubmitted() && $form->isValid()) {

                $email = $form->get('email')->getData();
                $newPassword = $form->get('newPassword')->getData();
    
                if ($email || $newPassword) {
                    if ($email && $newPassword) {
                        if ($user->getEmail() !== $email) {
                            $this->addFlash('danger', 'The email you entered does not match the email associated with your account.');
                        } else {
                            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                            $user->setPassword($hashedPassword);
                            try {
                                $mail = (new TemplatedEmail())
                                    ->from('support@luxury-services.com')
                                    ->to($user->getEmail())
                                    ->subject('Change of password')
                                    ->htmlTemplate('emails/change-password.html.twig');         
                
                                $mailer->send($mail);
                                $this->addFlash('success', 'Your password has been changed successfully!');
                            } catch (\Exception $e) {
                                $this->addFlash('danger', 'An error occurred while sending the message : ' . $e->getMessage());
                            }
                        }
                    } else {
                        $this->addFlash('danger', 'Email and password must be filled together to change password.');
                    }
                }
           

                // $entityManager->persist($candidat);
                $entityManager->flush();

                return $this->redirectToRoute('app_candidate_new');

                

            }
            $completionRate = $completionCalculator->calculateCompletion($candidat);
            return $this->render('profile/profile.html.twig', [
                'form' => $form,
                'candidate' => $candidat,
                'completionRate' => $completionRate,
               
            ]);
        }
    }
}

