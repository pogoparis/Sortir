<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


/*    public function configureFields(string $pageName): iterable
    {
        return [

            EmailField::new('email'),
            TextField::new('pseudo'),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('telephone'),
            BooleanField::new('is_admin'),
            BooleanField::new('is_actif'),
            BooleanField::new('is_verified')
        ];
    }*/


}
