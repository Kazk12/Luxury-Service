<?php

namespace App\Controller;

use App\Repository\CandidateRepository;
use App\Entity\Application;
use App\Repository\JobCategoryRepository;
use App\Repository\JobOfferRepository;
use App\Service\CandidateCompletionCalculator;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/')]
final class HomeController extends AbstractController
{
    #[Route(name: 'app_home_index')]
    public function index(
        JobOfferRepository $jobOfferRepository,
        JobCategoryRepository $jobRepository,
        EntityManagerInterface $entityManager,
        CandidateCompletionCalculator $completionCalculator,
        CandidateRepository $candidatRepository
    ): Response {


        /** 
         * @var User $user
         */
        $user = $this->getUser();

        if ($this->getUser() && $this->getUser()->isVerified() == false) {
            return $this->redirectToRoute('app_logout');
        }

        $jobsOffer = $jobOfferRepository->find10Jobs();


        if ($user) {
            $candidate = $candidatRepository->findOneBy(['user' => $user->getId()]);
            if ($candidate) {
                $completionRate = $completionCalculator->calculateCompletion($candidate);


                $categories = $jobRepository->findAll();
                $existingCandidatures = $entityManager->getRepository(Application::class)->findBy(['candidate' => $candidate]);


                return $this->render('home/home.html.twig', [
                    'categories' => $categories,
                    'job_offers' => $jobsOffer,
                    'completionRate' => $completionRate,
                    'existingCandidatures' => $existingCandidatures
                ]);
            }
        }



        return $this->render('home/home.html.twig', [
            'job_offers' => $jobsOffer,
        ]);
    }









    #[Route('/profilee',  name: 'profile')]
    public function profile(): Response
    {
        return $this->render('auth/profile.html.twig');
    }
}
