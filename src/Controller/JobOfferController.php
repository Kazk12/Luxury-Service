<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\JobOffer;
use App\Form\JobOfferType;
use App\Repository\CandidateRepository;
use App\Repository\JobCategoryRepository;
use App\Repository\JobOfferRepository;
use App\Service\CandidateCompletionCalculator;
use Doctrine\ORM\EntityManagerInterface;
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
         EntityManagerInterface $entityManager,
            CandidateCompletionCalculator $completionCalculator,
         CandidateRepository $candidatRepository,
      ): Response
    {

         /** 
         * @var User $user
         */
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $offres = $jobOfferRepository->findAllJobs();

       

        $candidate = $candidatRepository->findOneBy(['user' => $user->getId()]);
        // $jobOfferer = $jobOfferRepository->findLatestJobOffers();

        $completionRate = $completionCalculator->calculateCompletion($candidate);

       

        $categories = $jobRepository->findAll();

        $existingCandidatures = $entityManager->getRepository(Application::class)->findBy(['candidate' => $candidate]);
        return $this->render('job_offer/index.html.twig', [
            'job_offers' => $jobOfferRepository->findAllJobs(),
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

    // #[Route('/new', name: 'app_job_offer_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $jobOffer = new JobOffer();
    //     $form = $this->createForm(JobOfferType::class, $jobOffer);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($jobOffer);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_job_offer_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('job_offer/new.html.twig', [
    //         'job_offer' => $jobOffer,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_job_offer_show', methods: ['GET'])]
    // public function show(JobOffer $jobOffer): Response
    // {
    //     return $this->render('job_offer/show.html.twig', [
    //         'job_offer' => $jobOffer,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_job_offer_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, JobOffer $jobOffer, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(JobOfferType::class, $jobOffer);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_job_offer_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('job_offer/edit.html.twig', [
    //         'job_offer' => $jobOffer,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_job_offer_delete', methods: ['POST'])]
    // public function delete(Request $request, JobOffer $jobOffer, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$jobOffer->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($jobOffer);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_job_offer_index', [], Response::HTTP_SEE_OTHER);
    // }
}
