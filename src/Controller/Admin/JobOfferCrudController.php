<?php

namespace App\Controller\Admin;

use App\Entity\JobOffer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JobOfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobOffer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('jobTitle'),
            TextField::new('description'),
            TextField::new('location'),
            TextField::new('notes'),
            NumberField::new('salary'),
            BooleanField::new('active'),
            AssociationField::new('jobCategory')->autocomplete(),
            AssociationField::new('client')->autocomplete(),
            AssociationField::new('jobType')->autocomplete(),

        ];
    }
}
