<?php

declare(strict_types=1);

use Foundation\Http\Controllers\ErrorController;
use Foundation\Http\Controllers\HealthController;
use Foundation\Http\Controllers\HomeController;
use Maduser\Argon\Routing\Contracts\RouterInterface;

return static function (RouterInterface $router): void {
    $router->group(['web'], '', static function (RouterInterface $router): void {
        $router->get('/', HomeController::class, [], 'home');
        $router->get('/health', HealthController::class, [], 'health');
        $router->get('/error', ErrorController::class, [], 'error');
    });
};
