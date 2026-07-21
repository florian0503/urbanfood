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

    public function testNavIsPresentWithActiveLink(): void
    {
        $client = self::createClient();
        $client->request('GET', '/carte');

        self::assertSelectorExists('nav.uf-nav');
        self::assertSelectorTextContains('.uf-nav__link--active', 'La carte');
    }
}
