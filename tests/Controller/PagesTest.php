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
}
