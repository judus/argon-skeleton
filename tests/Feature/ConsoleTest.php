<?php

declare(strict_types=1);

namespace Tests\Feature;

use Symfony\Component\Console\Application;
use Tests\ApplicationTestCase;

final class ConsoleTest extends ApplicationTestCase
{
    public function testConsoleRuntimeRegistersApplicationCommands(): void
    {
        $container = $this->boot('console');
        $application = $container->get(Application::class);

        self::assertInstanceOf(Application::class, $application);
        self::assertTrue($application->has('app:about'));
    }
}
