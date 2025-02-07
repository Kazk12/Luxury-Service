<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JobsController extends AbstractController
{
    #[Route('/jobs',  name: 'jobs_index')]
    public function jobs(): Response
    {
        return $this->render('jobs/index.html.twig');
    }

    #[Route('/jobsShow',  name: 'jobs_show')]
    public function jobsShow(): Response
    {
        return $this->render('jobs/show.html.twig');
    }
}
