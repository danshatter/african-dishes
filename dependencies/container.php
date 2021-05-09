<?php

use Slim\Views\Twig;
use App\Base\TwigExtension;
use App\Validation\Validator;
use Slim\Flash\Messages;
use Slim\Csrf\Guard;
use Slim\Interfaces\RouteParserInterface;
use App\Exceptions\HttpPageExpiredException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

$container->set('settings', function() {
    return [
        'db' => [
            'host' => $_ENV['DB_HOST'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'database' => $_ENV['DB_NAME'],
            'prefix' => '',
            'collation' => 'utf8mb4_unicode_ci',
            'charset' => 'utf8mb4',
            'driver' => 'mysql'
        ],
    ];
});

$container->set('view', function() use ($container) {
    return Twig::create(__DIR__.'/../resources/views', ['cache' => false]);
});

$container->set(Twig::class, function() use ($container) {
    return $container->get('view');
});

$container->set(Validator::class, function() {
    return new Validator;
});

$container->set(Messages::class, function() {
    return new Messages;
});

$container->set('csrf', function() use ($app) {
    $guard = new Guard($app->getResponseFactory());

    if ($_ENV['APP_ENV'] === 'production') {
        $guard->setFailureHandler(function(Request $request, Handler $handler) {
            throw new HttpPageExpiredException($request);
            
            return $handler->handle($request);
        });
    }

    return $guard;
});

$container->set(Guard::class, function() use ($container) {
    return $container->get('csrf');
});

$container->set(RouteParserInterface::class, $routeParser);