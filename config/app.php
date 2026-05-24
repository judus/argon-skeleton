<?php

declare(strict_types=1);

return [
    'name' => $_ENV['APP_NAME'] ?? 'Argon App',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL),
    'version' => $_ENV['APP_VERSION'] ?? '0.1.0',
];
