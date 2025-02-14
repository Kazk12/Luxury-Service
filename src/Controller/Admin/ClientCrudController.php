<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Repository\ClientRepository;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClientCrudController extends AbstractCrudController
{

    private Security $security;
    private EntityRepository $entityRepository;
    private ClientRepository $clientRepository;

    public function __construct(
        Security $security,
        EntityRepository $entityRepository,
        ClientRepository $clientRepository
    ) {
        $this->security = $security;
        $this->entityRepository = $entityRepository;
        $this->clientRepository = $clientRepository;
    }


    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function createEntity(string $entityFqcn)
    {

        $client = new Client();
        $client->setUser($this->security->getUser());
        return $client;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ROLE_RECRUTEUR');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->entityRepository->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.user = :user')->setParameter('user', $this->getUser());

        return $response;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('compagyName'),
            TextField::new('activityType'),
            TextField::new('contactName'),
            TextField::new('workstation'),
            TextField::new('contactEmail'),
            TextField::new('contactNumber'),
        ];
    }


    public function configureActions(Actions $actions): Actions
    {
        $user = $this->security->getUser();
        $existingClient = $this->clientRepository->findOneBy(['user' => $user]);

        if ($existingClient) {
            return $actions
                ->disable(Action::NEW);
        }

        return $actions;
    }
}
