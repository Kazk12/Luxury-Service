<?php 

namespace App\Controller;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/')]
final class HomeController extends AbstractController
{
    #[Route(name: 'app_home_index')]
    public function index(): Response
    {
        return $this->render('home/home.html.twig');
    }

    #[Route('/jobs',  name: 'jobs_index')]
    public function jobs(): Response
    {
        return $this->render('jobs/index.html.twig');
    }

    #[Route('/jobsShow',  name: 'jobs_index')]
    public function jobsShow(): Response
    {
        return $this->render('jobs/show.html.twig');
    }

}