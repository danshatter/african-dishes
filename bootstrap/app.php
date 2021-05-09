<?php

session_start();

require_once __DIR__.'/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

// Environmental Variables Initialization
$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
$dotenv->required('APP_ENV')->allowedValues(['development', 'production']);

if ($_ENV['APP_ENV'] === 'production') {
    ini_set('display_errors', '0');
}

// Container Initialization
$builder = new ContainerBuilder;

$container = $builder->build();

// Application Initialization
AppFactory::setContainer($container);
$app = AppFactory::create();

// Route Parser
$routeParser = $app->getRouteCollector()->getRouteParser();

// Container Dependencies
require_once __DIR__.'/../dependencies/container.php';

//Database Initialization
require_once __DIR__.'/../dependencies/database.php';

// Global Middleware Stack
require_once __DIR__.'/../dependencies/middleware.php';

//API Routes (For JavaScript)
require_once __DIR__.'/../routes/api.php';

// Web Routes
require_once __DIR__.'/../routes/web.php';