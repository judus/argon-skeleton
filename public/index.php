<?php

declare(strict_types=1);

use Maduser\Argon\Prophecy\Argon;

require dirname(__DIR__) . '/vendor/autoload.php';

$bootstrap = require dirname(__DIR__) . '/bootstrap/app.php';

Argon::prophecy($bootstrap('http'));
