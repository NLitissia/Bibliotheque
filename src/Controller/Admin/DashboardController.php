<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Entity\Editeur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Nationalite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(AuteurCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bibliotheque');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
       yield MenuItem::linkToCrud('Auteur','fa fa-file-pdf',Auteur::class); 
       yield MenuItem::linkToCrud('Livre','fa fa-file-pdf',Livre::class); 
       yield MenuItem::linkToCrud('Nationalite','fa fa-file-pdf',Nationalite::class); 
       yield MenuItem::linkToCrud('Editeur','fa fa-file-pdf',Editeur::class); 
       yield MenuItem::linkToCrud('Genre','fa fa-file-pdf',Genre::class); 
    }
}
