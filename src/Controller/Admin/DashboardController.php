<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // Fonction pour te rerouter directement à la connexion sur le controller de son choix
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(OrderCrudController::class)->generateUrl());
        // Pour arriver sur la page initial du dashboard ->return parent::index();
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mon site E-commerce');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Catégorie', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-tag', Product::class);
        yield MenuItem::linkToCrud('Expedition', 'fas fa-truck', Carrier::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Order::class);


    }
}
