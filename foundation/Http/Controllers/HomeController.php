<?php

declare(strict_types=1);

namespace Foundation\Http\Controllers;

use Maduser\Argon\Http\Message\Response;

final readonly class HomeController
{
    public function __invoke(): Response
    {
        return Response::html(<<<HTML
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Argon App</title>
    <style>
        body {
            margin: 0;
            font-family: system-ui, sans-serif;
            color: #202124;
            background: #f6f8fa;
        }
        main {
            max-width: 760px;
            margin: 10vh auto;
            padding: 32px;
        }
    </style>
</head>
<body>
<main>
    <h1>Argon App</h1>
    <p>Your application skeleton is running.</p>
</main>
</body>
</html>
HTML);
    }
}
