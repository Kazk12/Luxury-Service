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
use App\Interfaces\PasswordUpdaterInterface;
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
        PasswordUpdaterInterface $passwordUpdater,
        
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
    
                if ($email && $newPassword) {
                    $passwordUpdater->updatePassword($user, $email, $newPassword);
                } elseif ($email || $newPassword) {
                    $this->addFlash('danger', 'Email and password must be filled together to change password.');
                }
           

                $entityManager->persist($candidat);
                $entityManager->flush();

                $this->addFlash('success', 'Profile updated successfully!');

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






    #[Route('/profile/delete/{id}', name: 'app_profile_delete')]
    public function delete(
        Candidate $candidate,
        EntityManagerInterface $entityManager,
    ): Response {
        // verifie si la personne qui supprime est celle qui est connecté
        /** @var User */
        $user = $this->getUser();
        if ($user->getCandidate() !== $candidate) {
            $this->addFlash('danger', 'You are not allowed to delete this profile!, the admin will be informed of this action.');

            return $this->redirectToRoute('app_profile');
        }

        $candidate->setDeletedAt(new \DateTimeImmutable());
        $user->setRoles(['ROLE_DELETED']);
        $entityManager->flush();

        // $this->addFlash('success', 'Profile deleted successfully!');

        return $this->redirectToRoute('app_logout');
    }

  
}

