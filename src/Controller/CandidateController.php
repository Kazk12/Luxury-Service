<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CandidateType;
use App\Entity\User;
use App\Entity\Candidate;
use App\Repository\CandidateRepository;

#[Route('/candidate')]
final class CandidateController extends AbstractController
{
    #[Route('/profile', name: 'app_candidate_new', methods: ['GET', 'POST'])]
    public function index(
        CandidateRepository $candidatRepository,
        Request $request,
        EntityManagerInterface $entityManager
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
                // $entityManager->persist($candidat);
                $entityManager->flush();

                

            }

            return $this->render('profile/profile.html.twig', [
                'form' => $form,
                'candidate' => $candidat,
            ]);
        }
    }
}

