<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PagesTest extends WebTestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function pageProvider(): iterable
    {
        yield 'accueil' => ['/'];
        yield 'carte' => ['/carte'];
        yield 'histoire' => ['/histoire'];
        yield 'contact' => ['/contact'];
    }

    #[DataProvider('pageProvider')]
    public function testPageIsSuccessful(string $url): void
    {
        $client = self::createClient();
        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }

    public function testHomeShowsFeaturedItems(): void
    {
        $client = self::createClient();
        $client->request('GET', '/');

        self::assertSelectorCount(3, '.uf-featured__item');
        self::assertSelectorTextContains('.uf-featured__list', 'Le Carnivore');
    }

    public function testCarteShowsAllCategories(): void
    {
        $client = self::createClient();
        $client->request('GET', '/carte');

        self::assertSelectorCount(4, '.uf-menu__cat');
        self::assertSelectorTextContains('.uf-menu', 'Tacos');
        self::assertSelectorTextContains('.uf-student__title', 'Formule étudiant');
    }

    public function testHistoireShowsTimeline(): void
    {
        $client = self::createClient();
        $client->request('GET', '/histoire');

        self::assertSelectorCount(4, '.uf-timeline__step');
        self::assertSelectorTextContains('.uf-page-head__title', "D'un camion");
    }

    public function testNavIsPresentWithActiveLink(): void
    {
        $client = self::createClient();
        $client->request('GET', '/carte');

        self::assertSelectorExists('nav.uf-nav');
        self::assertSelectorTextContains('.uf-nav__link--active', 'La carte');
    }

    public function testNavIsOverlayOnHomeOnly(): void
    {
        $client = self::createClient();

        $client->request('GET', '/');
        self::assertSelectorExists('nav.uf-nav--overlay');

        $client->request('GET', '/carte');
        self::assertSelectorNotExists('nav.uf-nav--overlay');
    }

    public function testLegalPagesAreSuccessful(): void
    {
        $client = self::createClient();

        $client->request('GET', '/mentions-legales');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('.uf-page-head__title', 'Mentions légales');

        $client->request('GET', '/confidentialite');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('.uf-page-head__title', 'Confidentialité');
    }

    public function testContactShowsRgpdMention(): void
    {
        $client = self::createClient();
        $client->request('GET', '/contact');

        self::assertSelectorExists('.uf-form__rgpd a[href="/confidentialite"]');
    }

    public function testSitemapListsPublicPages(): void
    {
        $client = self::createClient();
        $client->request('GET', '/sitemap.xml');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/xml; charset=utf-8');
        $content = (string) $client->getResponse()->getContent();
        self::assertStringContainsString('/carte</loc>', $content);
        self::assertStringNotContainsString('/admin', $content);
    }

    public function testHomeHasStructuredData(): void
    {
        $client = self::createClient();
        $client->request('GET', '/');

        $content = (string) $client->getResponse()->getContent();
        self::assertStringContainsString('application/ld+json', $content);
        self::assertStringContainsString('"@type": "Restaurant"', $content);
    }

    public function testLoginIsNoindex(): void
    {
        $client = self::createClient();
        $client->request('GET', '/login');

        self::assertSelectorExists('meta[name="robots"][content="noindex"]');
    }

    public function testHomeHasOpenBadge(): void
    {
        $client = self::createClient();
        $client->request('GET', '/');

        self::assertSelectorExists('.uf-open-badge');
    }

    public function testCookieBannerIsPresent(): void
    {
        $client = self::createClient();
        $client->request('GET', '/');

        self::assertSelectorExists('.uf-cookies');
        self::assertSelectorExists('.uf-cookies .uf-cookies__accept');
        self::assertSelectorExists('.uf-cookies .uf-cookies__refuse');
        self::assertSelectorExists('.uf-cookies .uf-cookies__custom');
        self::assertSelectorExists('button.uf-cookies-manage');
    }

    public function testScrollTopButtonIsPresent(): void
    {
        $client = self::createClient();
        $client->request('GET', '/carte');

        self::assertSelectorExists('button.uf-totop');
    }
}
