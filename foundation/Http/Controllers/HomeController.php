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
        a {
            color: #0969da;
        }
        code {
            padding: 2px 5px;
            border-radius: 4px;
            background: #eaeef2;
        }
    </style>
</head>
<body>
<main>
    <h1>Argon App</h1>
    <p>Your application skeleton is running.</p>
    <ul>
        <li><a href="/health"><code>/health</code></a> returns a JSON health response.</li>
        <li><a href="/error"><code>/error</code></a> demonstrates the default exception policy.</li>
    </ul>
</main>
</body>
</html>
HTML);
    }
}
