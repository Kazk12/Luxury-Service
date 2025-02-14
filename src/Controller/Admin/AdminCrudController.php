<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AdminCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW); // Désactiver l'action NEW pour empêcher l'ajout de nouveaux clients
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('compagyName'),
            TextField::new('activityType'),
            TextField::new('contactName'),
            TextField::new('notes'),
            TextField::new('workstation'),
            TextField::new('contactEmail'),
            TextField::new('contactNumber'),
            AssociationField::new('user')->autocomplete(),
        ];
    }
}