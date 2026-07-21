<?php

namespace App\Command;

use App\Repository\ContactMessageRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:purge-contact-messages',
    description: 'Supprime les messages de contact de plus de 12 mois (retention RGPD)',
)]
final class PurgeContactMessagesCommand extends Command
{
    public function __construct(
        private readonly ContactMessageRepository $contactMessageRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $before = new \DateTimeImmutable('-12 months');

        $count = $this->contactMessageRepository->purgeOlderThan($before);

        $io->success(sprintf('%d message(s) de plus de 12 mois supprime(s).', $count));

        return Command::SUCCESS;
    }
}
