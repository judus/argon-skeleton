<?php

declare(strict_types=1);

namespace Tests\Skeleton\Feature;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Skeleton\ApplicationTestCase;

final class ConsoleTest extends ApplicationTestCase
{
    public function testConsoleRuntimeRegistersApplicationCommands(): void
    {
        $container = $this->boot('console');
        $application = $container->get(Application::class);

        self::assertInstanceOf(Application::class, $application);
        self::assertTrue($application->has('app:about'));
    }

    public function testAboutCommandDisplaysApplicationInformation(): void
    {
        $container = $this->boot('console');
        $application = $container->get(Application::class);

        self::assertInstanceOf(Application::class, $application);

        $tester = new CommandTester($application->find('app:about'));
        $tester->execute([]);

        self::assertSame(0, $tester->getStatusCode());
        self::assertStringContainsString('Argon App', $tester->getDisplay());
        self::assertStringContainsString('Environment: production', $tester->getDisplay());
        self::assertStringContainsString('Version: 0.1.0', $tester->getDisplay());
    }
}
