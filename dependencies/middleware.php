<?php

use Slim\Views\TwigMiddleware;
use Slim\Middleware\MethodOverrideMiddleware;
use App\Error\HtmlErrorRenderer;
use App\Middlewares\TwigMiddleware as CustomTwigMiddleware;
use Slim\Logger;

// Custom Twig Middleware
$app->add(CustomTwigMiddleware::class);

// Twig Middleware
$app->add(TwigMiddleware::createFromContainer($app));

// Routing Middleware
$app->addRoutingMiddleware();

// Method Override Middleware
$app->add(MethodOverrideMiddleware::class);

// Logger Inintialization
$logger = new Logger;

// Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true, $logger);

if ($_ENV['APP_ENV'] === 'production') {
    $errorMiddleware->getDefaultErrorHandler()->registerErrorRenderer('text/html', HtmlErrorRenderer::class);
}

