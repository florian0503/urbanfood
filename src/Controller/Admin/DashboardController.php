<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem as AdminMenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
    ) {
    }

    public function index(): Response
    {
        return $this->redirect(
            $this->adminUrlGenerator->setController(MenuCategoryCrudController::class)->generateUrl(),
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle("Urban's Food — Admin");
    }

    public function configureMenuItems(): iterable
    {
        yield AdminMenuItem::linkTo(MenuCategoryCrudController::class, 'Catégories', 'fa fa-folder');
        yield AdminMenuItem::linkTo(MenuItemCrudController::class, 'Plats', 'fa fa-utensils');
        yield AdminMenuItem::linkTo(ContactMessageCrudController::class, 'Messages', 'fa fa-envelope');
        yield AdminMenuItem::linkToRoute('Retour au site', 'fa fa-arrow-left', 'app_home');
        yield AdminMenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out');
    }
}
