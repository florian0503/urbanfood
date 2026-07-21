<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\InMemoryUser;

final class AdminTest extends WebTestCase
{
    public function testAdminRequiresAuthentication(): void
    {
        $client = self::createClient();
        $client->request('GET', '/admin');

        self::assertResponseRedirects('http://localhost/login');
    }

    public function testLoginPageIsSuccessful(): void
    {
        $client = self::createClient();
        $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('input[name="_username"]');
    }

    public function testAdminAccessibleWhenLoggedIn(): void
    {
        $client = self::createClient();
        $client->loginUser(new InMemoryUser('admin', null, ['ROLE_ADMIN']));
        $client->request('GET', '/admin');
        $client->followRedirect();

        self::assertResponseIsSuccessful();
    }
}
