<?php

declare(strict_types=1);

namespace Foundation\Console\Command;

use Maduser\Argon\Container\ArgonContainer;
use Override;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-api
 */
final class AboutCommand extends Command
{
    public function __construct(
        private readonly ArgonContainer $container,
    ) {
        parent::__construct('app:about');
    }

    #[Override]
    protected function configure(): void
    {
        $this->setDescription('Display basic application information.');
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $parameters = $this->container->getParameters();

        $output->writeln(sprintf('<info>%s</info>', (string) $parameters->get('app.name', 'Argon App')));
        $output->writeln(sprintf('Environment: %s', (string) $parameters->get('app.env', 'production')));
        $output->writeln(sprintf('Version: %s', (string) $parameters->get('app.version', 'UNKNOWN')));

        return Command::SUCCESS;
    }
}
