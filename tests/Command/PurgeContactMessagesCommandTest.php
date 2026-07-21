<?php

namespace App\Tests\Command;

use App\Entity\ContactMessage;
use App\Repository\ContactMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class PurgeContactMessagesCommandTest extends KernelTestCase
{
    public function testOldMessagesArePurgedAndRecentOnesKept(): void
    {
        self::bootKernel();
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $repository = self::getContainer()->get(ContactMessageRepository::class);

        $oldMessage = $this->createMessage('Vieux message');
        $this->forceCreatedAt($oldMessage, new \DateTimeImmutable('-13 months'));
        $recentMessage = $this->createMessage('Message recent');

        $entityManager->persist($oldMessage);
        $entityManager->persist($recentMessage);
        $entityManager->flush();

        $countBefore = $repository->count([]);

        $application = new Application(self::$kernel);
        $tester = new CommandTester($application->find('app:purge-contact-messages'));
        $tester->execute([]);

        $tester->assertCommandIsSuccessful();
        self::assertStringContainsString('supprime', $tester->getDisplay());
        self::assertSame($countBefore - 1, $repository->count([]));
        self::assertNotNull($recentMessage->getId());
    }

    private function createMessage(string $subject): ContactMessage
    {
        $message = new ContactMessage();
        $message->setName('Test');
        $message->setEmail('test@example.com');
        $message->setSubject($subject);
        $message->setMessage('Contenu de test');

        return $message;
    }

    private function forceCreatedAt(ContactMessage $message, \DateTimeImmutable $date): void
    {
        $property = new \ReflectionProperty(ContactMessage::class, 'createdAt');
        $property->setValue($message, $date);
    }
}
