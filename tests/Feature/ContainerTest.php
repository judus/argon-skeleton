<?php

declare(strict_types=1);

namespace Tests\Feature;

use InvalidArgumentException;
use Tests\ApplicationTestCase;

final class ContainerTest extends ApplicationTestCase
{
    public function testApplicationParametersAreAvailableInContainer(): void
    {
        $container = $this->boot('http');
        $parameters = $container->getParameters();

        self::assertSame('Argon App', $parameters->get('app.name'));
        self::assertSame('production', $parameters->get('app.env'));
        self::assertSame('0.1.0', $parameters->get('app.version'));
    }

    public function testUnsupportedRuntimeFailsExplicitly(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported Argon runtime [worker].');

        $this->boot('worker');
    }
}
