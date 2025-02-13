<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Candidate;
use App\Form\ApplicationType;
use App\Entity\User;

use App\Repository\ApplicationRepository;
use App\Repository\CandidateRepository;
use App\Repository\JobCategoryRepository;
use App\Repository\JobOfferRepository;
use App\Service\CandidateCompletionCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/application')]
final class ApplicationController extends AbstractController
{
    // #[Route(name: 'app_application_index', methods: ['GET'])]
    // public function index(ApplicationRepository $applicationRepository): Response
    // {
    //     return $this->render('application/index.html.twig', [
    //         'applications' => $applicationRepository->findAll(),
    //     ]);
    // }

    #[Route( name: 'app_application_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CandidateCompletionCalculator $completionCalculator,
         EntityManagerInterface $entityManager,
          CandidateRepository $candidatRepository,
           JobOfferRepository $jobOfferRepository,
            JobCategoryRepository $jobRepository
            ): Response
    {

      
        $categories = $jobRepository->findAll();

         /** 
         * @var User $user
         */
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $application = new Application();
        $allApplication = $entityManager->getRepository(Application::class)->findAll();
        $candidate = $candidatRepository->findOneBy(['user' => $user->getId()]);
        $jobOffer = $jobOfferRepository->findOneBy(['id' => $request->query->get('id')]);

       

        

        $application->setJobOffer($jobOffer);
        $application->setCandidate($candidate);

        $existingCandidature = $entityManager->getRepository(Application::class)->findOneBy([
            'candidate' => $candidate,
            'jobOffer' => $jobOffer,
        ]);

        if ($existingCandidature) {
            $this->addFlash('error', 'Vous avez déjà postulé à cette offre.');
            return $this->render('job_offer/index.html.twig', [
                'job_offers' => $jobOfferRepository->findLatestJobOffers(),
                'categories' => $categories
            ]);
        }

        $completionRate = $completionCalculator->calculateCompletion($candidate);

        if($completionRate < 100){
            $this->addFlash('error', 'Vous devez compléter votre profil à 100% pour postuler à une offre.');
            return $this->redirectToRoute('app_candidate_new');
        };



        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);
       
       
     


        $entityManager->persist($application);
        $entityManager->flush();

            

        // if ($form->isSubmitted() && $form->isValid()) {

          
            
           

        //     return $this->redirectToRoute('app_home_index', [], Response::HTTP_SEE_OTHER);
        // }

        return $this->render('home/home.html.twig', [
            'application' => $application,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'app_application_show', methods: ['GET'])]
    // public function show(Application $application): Response
    // {
    //     return $this->render('application/show.html.twig', [
    //         'application' => $application,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_application_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Application $application, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(ApplicationType::class, $application);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_application_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('application/edit.html.twig', [
    //         'application' => $application,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_application_delete', methods: ['POST'])]
    // public function delete(Request $request, Application $application, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$application->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($application);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_application_index', [], Response::HTTP_SEE_OTHER);
    // }
}
