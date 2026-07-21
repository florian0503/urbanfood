<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SitemapController extends AbstractController
{
    private const ROUTES = [
        'app_home',
        'app_carte',
        'app_histoire',
        'app_contact',
        'app_mentions_legales',
        'app_confidentialite',
    ];

    #[Route('/sitemap.xml', name: 'app_sitemap')]
    public function index(): Response
    {
        $urls = array_map(
            fn (string $route): string => $this->generateUrl($route, [], UrlGeneratorInterface::ABSOLUTE_URL),
            self::ROUTES,
        );

        $response = $this->render('sitemap/index.xml.twig', [
            'urls' => $urls,
        ]);
        $response->headers->set('Content-Type', 'application/xml; charset=utf-8');

        return $response;
    }
}
