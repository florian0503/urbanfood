<?php

namespace App\Controller;

use App\Repository\MenuItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MenuItemRepository $menuItemRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'featured_items' => $menuItemRepository->findFeatured(),
        ]);
    }
}
