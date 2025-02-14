<?php

namespace App\Controller\Admin;

use App\Entity\Application;
use App\Entity\Candidate;
use App\Repository\CandidateRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\DomCrawler\Field\FileFormField;

class ApplicationCrudController extends AbstractCrudController
{
    private Security $security;
    private EntityRepository $entityRepository;
    private CandidateRepository $candidateRepository;

    public function __construct(
        Security $security,
        EntityRepository $entityRepository,
        CandidateRepository $candidateRepository
    ) {
        $this->security = $security;
        $this->entityRepository = $entityRepository;
        $this->candidateRepository = $candidateRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Application::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $application = new Application();
        return $application;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW); // Désactiver l'action NEW pour empêcher l'ajout de nouvelles applications
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->entityRepository->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->innerJoin('entity.jobOffer', 'jobOffer')
            ->innerJoin('entity.candidate', 'candidate')
            ->andWhere('jobOffer.client = :client')
            ->setParameter('client', $this->security->getUser()->getClient());

        return $response;
    }

    public function configureFields(string $pageName): iterable
    {


        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            return [
                IdField::new('id')->hideOnForm(),
                TextField::new('candidate.lastName', 'Nom du candidat'),
                TextField::new('candidate.firstName', 'Nom du candidat'),
                TextField::new('jobOffer.jobTitle', 'Titre du job'),
                TextField::new('jobOffer.salary', 'Salaire proposé'),
                TextField::new('jobOffer.jobCategory', 'La catégorie de l\'offre d\'emploi'),
                TextField::new('status', 'Statut'),
            ];
        } elseif ($pageName === Crud::PAGE_EDIT) {
            return [
                IdField::new('id')->hideOnForm(),
                UrlField::new('candidate.cv', 'CV du candidat')
                    ->setFormTypeOption('required', false)
                    


                    ->setFormTypeOption('allow_delete', false),

                ChoiceField::new('status', 'Statut')
                    ->setChoices([
                        'En attente' => 'pending',
                        'Acceptée' => 'accepted',
                        'Refusée' => 'rejected'
                    ]),
            ];
        }

        return [];
    }
}
