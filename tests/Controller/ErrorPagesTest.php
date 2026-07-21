<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ErrorPagesTest extends WebTestCase
{
    public function test404PageIsBranded(): void
    {
        $client = self::createClient(['debug' => false]);
        $client->request('GET', '/cette-page-nexiste-pas');

        self::assertResponseStatusCodeSame(404);
        self::assertSelectorTextContains('.uf-error__title', 'Perdu ?');
        self::assertSelectorExists('.uf-error__cta');
    }
}
