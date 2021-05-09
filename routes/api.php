<?php

use Slim\Routing\RouteCollectorProxy as Group;
use App\Controllers\APIController;
use Slim\Middleware\BodyParsingMiddleware;

$app->group('/api', function(Group $group) {
    // $group->get('/test', APIController::class.':test');
    $group->post('/menu', APIController::class.':MenuList');
    $group->get('/menu/{category}', APIController::class.':getCategoryMenuList');
    $group->post('/cart', APIController::class.':getCart');

})->add(BodyParsingMiddleware::class);