<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\JobOffer;
use App\Entity\OffreEmploi;
use App\Entity\User;
use App\Repository\JobOfferRepository;
use App\Repository\OffreEmploiRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;

class JobOfferCrudController extends AbstractCrudController
{

    private Security $security;
    private EntityRepository $entityRepository;
    private JobOfferRepository $jobOfferRepository;

    public function __construct(
        Security $security,
        EntityRepository $entityRepository,
        JobOfferRepository $jobOfferRepository
    ) {
        $this->security = $security;
        $this->entityRepository = $entityRepository;
        $this->jobOfferRepository = $jobOfferRepository;
    }




    public static function getEntityFqcn(): string
    {
        return JobOffer::class;
    }

    public function createEntity(string $entityFqcn)
    {
        /** 
         * @var User $user
         */
        $user = $this->getUser();

        $jobOffer = new JobOffer();
        $jobOffer->setClient($user->getClient());
        return $jobOffer;
    }

    

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        /** 
         * @var User $user
         */
        $user = $this->getUser();
        $response = $this->entityRepository->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            $response->andWhere('entity.client = :client')->setParameter('client', $user->getClient());
        }

        return $response;
    }




    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('jobTitle'),
            TextField::new('description'),
            TextField::new('location'),
            // TextField::new('notes'),
            TextField::new('reference'),
            NumberField::new('salary'),
            BooleanField::new('active'),
            AssociationField::new('jobCategory')->autocomplete(),
            // AssociationField::new('client')->autocomplete(),
            AssociationField::new('jobType')->autocomplete(),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $user = $this->getUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_ADMIN', $roles)) {
            return $actions
                ->disable(Action::EDIT, Action::DELETE, Action::NEW);
        }

        return $actions;
    }

    




    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        /** 
         * @var User $user
         */
        $user = $this->getUser();

        if ($entityInstance instanceof JobOffer) {

            $entityInstance->setClient($user->getClient());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** 
         * @var User $user
         */
        $user = $this->getUser();

        if ($entityInstance instanceof JobOffer) {

            $entityInstance->setClient($user->getClient());
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
