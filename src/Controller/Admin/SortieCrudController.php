<?php

namespace App\Controller\Admin;

use App\Entity\Etat;
use App\Entity\Sortie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SortieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sortie::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            DateTimeField::new('dateHeureDebut'),
            DateTimeField::new('dateHeureFin'),
            DateTimeField::new('dateLimiteInscription'),
            IntegerField::new('nbInscriptionsMax'),
            AssociationField::new('etat'),
            TextField::new('infosSortie'),
        ];
    }

}
