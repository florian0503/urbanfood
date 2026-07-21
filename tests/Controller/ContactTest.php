<?php

namespace App\Tests\Controller;

use App\Repository\ContactMessageRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ContactTest extends WebTestCase
{
    public function testValidSubmissionIsStored(): void
    {
        $client = self::createClient();
        $repository = static::getContainer()->get(ContactMessageRepository::class);
        $countBefore = $repository->count([]);

        $client->request('GET', '/contact');
        $client->submitForm('Envoyer', [
            'contact_message[name]' => 'Jean Test',
            'contact_message[email]' => 'jean@example.com',
            'contact_message[subject]' => 'Privatisation',
            'contact_message[message]' => 'Bonjour, le local est-il disponible samedi soir ?',
        ]);

        self::assertResponseRedirects('/contact');
        self::assertEmailCount(1);
        $client->followRedirect();
        self::assertSelectorTextContains('.uf-form__submit', 'Envoyé');
        self::assertSame($countBefore + 1, $repository->count([]));
    }

    public function testHoneypotSubmissionIsNotStored(): void
    {
        $client = self::createClient();
        $repository = static::getContainer()->get(ContactMessageRepository::class);
        $countBefore = $repository->count([]);

        $client->request('GET', '/contact');
        $client->submitForm('Envoyer', [
            'contact_message[name]' => 'Bot',
            'contact_message[email]' => 'bot@example.com',
            'contact_message[subject]' => 'Spam',
            'contact_message[message]' => 'Spam message',
            'contact_message[website]' => 'https://spam.example.com',
        ]);

        self::assertResponseRedirects('/contact');
        self::assertEmailCount(0);
        self::assertSame($countBefore, $repository->count([]));
    }

    public function testInvalidEmailIsRejected(): void
    {
        $client = self::createClient();
        $repository = static::getContainer()->get(ContactMessageRepository::class);
        $countBefore = $repository->count([]);

        $client->request('GET', '/contact');
        $client->submitForm('Envoyer', [
            'contact_message[name]' => 'Jean Test',
            'contact_message[email]' => 'pas-un-email',
            'contact_message[subject]' => 'Question',
            'contact_message[message]' => 'Bonjour',
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertSame($countBefore, $repository->count([]));
    }
}
