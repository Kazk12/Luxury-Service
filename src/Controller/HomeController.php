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
    ): Response
    {


         /** 
         * @var User $user
         */
        $user = $this->getUser();

        if($this->getUser() && $this->getUser()->isVerified() == false){
            return $this->redirectToRoute('app_logout');
        } 

        
        $candidate = $candidatRepository->findOneBy(['user' => $user->getId()]);

        $completionRate = $completionCalculator->calculateCompletion($candidate);

        $jobsOffer = $jobOfferRepository->find10Jobs();

        $categories = $jobRepository->findAll();
        $existingCandidatures = $entityManager->getRepository(Application::class)->findBy(['candidate' => $candidate]);
   
            return $this->render('home/home.html.twig', [
                'categories' => $categories,
                'jobsOffer' => $jobsOffer,
                'completionRate' => $completionRate,
                'existingCandidatures' => $existingCandidatures
            ]);

        
    }

    

   

   

   

    #[Route('/profilee',  name: 'profile')]
    public function profile(): Response
    {
        return $this->render('auth/profile.html.twig');
    }

}