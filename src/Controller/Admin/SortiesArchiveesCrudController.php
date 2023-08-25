<?php

namespace App\Controller\Admin;

use App\Entity\SortiesArchivees;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SortiesArchiveesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SortiesArchivees::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
