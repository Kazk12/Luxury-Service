<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\JobOffer;
use App\Repository\CandidateRepository;
use App\Repository\JobCategoryRepository;
use App\Repository\JobOfferRepository;
use App\Service\CandidateCompletionCalculator;
use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/job')]
final class JobOfferController extends AbstractController
{
    #[Route(name: 'All_Jobs',  methods: ['GET'])]
    public function index(
        JobOfferRepository $jobOfferRepository,
        JobCategoryRepository $jobRepository,
        Request $request,
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManager,
        CandidateCompletionCalculator $completionCalculator,
        CandidateRepository $candidatRepository,
    ): Response {

        /** 
         * @var User $user
         */

        $user = $this->getUser();

        $queryBuilder = $jobOfferRepository->findAllJobs();

        $categories = $jobRepository->findAll();

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        
         if (!$user) {
            return $this->render('job_offer/jobNoConnected.html.twig', [
                'job_offers' => $queryBuilder,
                'pagination' => $pagination,
                'categories' => $categories,
            ]);
        }




        $candidate = $candidatRepository->findOneBy(['user' => $user->getId()]);

        $completionRate = $completionCalculator->calculateCompletion($candidate);



       

      

    

        $existingCandidatures = $entityManager->getRepository(Application::class)->findBy(['candidate' => $candidate]);
        return $this->render('job_offer/index.html.twig', [
            'job_offers' => $queryBuilder,
            'pagination' => $pagination,
            'categories' => $categories,
            'existingCandidatures' => $existingCandidatures,
            'completionRate' => $completionRate,
        ]);
    }



    #[Route('/{id}', name: 'app_job_offer_show', methods: ['GET'])]
    public function show(JobOffer $jobOffer): Response
    {
        return $this->render('job_offer/show.html.twig', [
            'job_offer' => $jobOffer,
        ]);
    }
}
