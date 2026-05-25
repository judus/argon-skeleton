<?php

declare(strict_types=1);

use Foundation\Providers\HttpFoundationServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Prophecy\Argon;

require dirname(__DIR__) . '/vendor/autoload.php';

Argon::prophecy(static function (ArgonContainer $container): void {
    $container->register(HttpFoundationServiceProvider::class);
});
