<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CompagnyController extends AbstractController
{
    #[Route('/compagny',  name: 'compagny_index')]
    public function compagny(): Response
    {
        return $this->render('compagny/compagny.html.twig');
    }
}
