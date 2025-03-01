<?php

namespace App\Controller\Admin;

use App\Entity\Candidate;
use App\Entity\Client;
use App\Entity\Experience;
use App\Entity\Gender;
use App\Entity\JobCategory;
use App\Entity\JobOffer;
use App\Entity\TypeJob;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Controller\Admin\Client2CrudController;
use App\Entity\Application;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {

    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
                    ->setTitle('Luxury Service')
                    ->setFaviconPath('img/luxury-services-logo.png');
    }

       public function configureMenuItems(): iterable
    {
         /** 
         * @var User $user
         */
        $user = $this->getUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_ADMIN', $roles)) {


        yield MenuItem::linkToDashboard('Dashboard', 'fa fatachometer-alt');
        yield MenuItem::section('Jobs');

         
        yield MenuItem::section('Candidates');
        yield MenuItem::linkToCrud('Gender', 'fas fa-venus-mars', Gender::class);
        yield MenuItem::linkToCrud('Experience', 'fas fa-briefcase', Experience::class);
        yield MenuItem::linkToCrud('Job Category', 'fas fa-th-large', JobCategory::class);

        yield MenuItem::section('Recruters');
        yield MenuItem::linkToCrud('Recruters', 'fas fa-user-tie', User::class);
        yield MenuItem::linkToCrud('Client', 'fas fa-user-tie', Client::class)
        // ->setPermission('ROLE_ADMIN')
        ->setController(AdminCrudController::class);


        yield MenuItem::section('Job Offers');
        yield MenuItem::linkToCrud('Job Type', 'fas fa-user-tie', TypeJob::class);
        yield MenuItem::linkToCrud('Job Offer', 'fas fa-user-tie', JobOffer::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
    if(in_array('ROLE_RECRUTEUR', $roles)){
        yield MenuItem::linkToDashboard('Dashboard', 'fa fatachometer-alt');
        yield MenuItem::section('Job Offers');
        yield MenuItem::linkToCrud('Job Offer', 'fas fa-user-tie', JobOffer::class);
        
        yield MenuItem::section('Fill your profile', 'fa fa-user-tie');
         /** @var User */
         $user = $this->getUser();
         $client = $user->getClient();
 
 
         // Générer l'URL de la page d'édition du profil Client
         $url = $this->adminUrlGenerator
             ->setController(ClientCrudController::class)
             ->setAction('edit')
             ->setEntityId($client->getId())
             ->generateUrl();

             yield MenuItem::linkToUrl('Here', 'fa fa-arrow-right', $url);

        yield MenuItem::section('Candidates');
        yield MenuItem::linkToCrud('Candidate', 'fas fa-user-tie', Application::class);



       
    }
}
}
