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
        if($this->getUser() && $this->getUser()->isVerified() == false){
            return $this->redirectToRoute('app_logout');
        } else {
            return $this->render('home/home.html.twig');

        }
    }

    

   

   

   

    #[Route('/profilee',  name: 'profile')]
    public function profile(): Response
    {
        return $this->render('auth/profile.html.twig');
    }

}