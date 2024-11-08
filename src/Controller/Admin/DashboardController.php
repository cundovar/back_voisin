<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Objet;
use App\Entity\Utilisateur;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Redirige vers la liste de l'entité
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UtilisateurCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mon Application');
    }

    public function configureMenuItems(): iterable
    {
        // Exemple d'ajout de lien vers une entité
        yield MenuItem::linkToCrud('User', 'fas fa-list', Utilisateur::class);
        yield MenuItem::linkToCrud('Objet', 'fas fa-list', Objet::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
        
        // Autres éléments de menu peuvent être ajoutés ici
    }
}
