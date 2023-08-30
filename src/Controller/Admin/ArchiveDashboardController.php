<?php

namespace App\Controller\Admin;

use App\Entity\Sortie;
use App\Entity\SortiesArchivees;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ArchiveDashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
             return $this->render('admin/sortiesArchivees.html.twig');
    }

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->updateAdminRole($entityInstance);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->updateAdminRole($entityInstance);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    private function updateAdminRole(User $user): void
    {
        if ($user->isIsAdmin()) {
            $user->setRoles(array_merge($user->getRoles(), ['ROLE_ADMIN']));
        } else {
            $user->setRoles(array_diff($user->getRoles(), ['ROLE_ADMIN']));
        }

        // Hasher le mot de passe si nécessaire
        if ($user->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($hashedPassword);
        }
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sortir');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Sorties Archivées', 'fas fa-list', SortiesArchivees::class);
        yield MenuItem::linkToCrud('Sorties en cours', 'fas fa-list', Sortie::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
    }
}
