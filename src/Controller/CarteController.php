<?php

namespace App\Controller;

use App\Repository\MenuCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CarteController extends AbstractController
{
    #[Route('/carte', name: 'app_carte')]
    public function index(MenuCategoryRepository $menuCategoryRepository): Response
    {
        return $this->render('carte/index.html.twig', [
            'categories' => $menuCategoryRepository->findAllOrdered(),
        ]);
    }
}
