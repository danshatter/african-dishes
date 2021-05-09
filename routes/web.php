<?php

use App\Controllers\{UserController, ViewController, CategoryController, MenuController, OrderController, LocationController};
use App\Middlewares\{AlreadyLoggedInMiddleware, GetCartMiddleware, LoggedInOnlyMiddleware, OrderCompleteMiddleware, AuthenticatedMiddleware, AdminOnlyMiddleware, RecoveryStateOnlyMiddleware};
use Slim\Routing\RouteCollectorProxy as Group;
use Slim\Csrf\Guard;

// Routes Accessible Whether Authenticated or Not
$app->group('', function(Group $group) {
    $group->get('/', ViewController::class.':index')->setName('home');

    $group->get('/cart', ViewController::class.':cart')->setName('cart');

    $group->get('/checkout', ViewController::class.':checkout')->setName('checkout');
    $group->post('/checkout', OrderController::class.':store')->add(GetCartMiddleware::class);

    $group->get('/food-list', ViewController::class.':foodList')->setName('food-list');

    $group->get('/order-complete', OrderController::class.':complete')->setName('order-complete')->add(OrderCompleteMiddleware::class);

    $group->get('/category/{category}', ViewController::class.':displayCategoryitems')->setName('show-category');

})->add(AuthenticatedMiddleware::class)->add(Guard::class);
// End of Routes Accessible Whether Authenticated or Not


// Routes Inaccessible When Logged In
$app->group('', function (Group $group) {
    $group->get('/register', ViewController::class.':signUp')->setName('sign-up');
    $group->post('/register', UserController::class.':store');

    $group->get('/login', ViewController::class.':signIn')->setName('sign-in');
    $group->post('/login', UserController::class.':show');

    $group->get('/forgotten-password', ViewController::class.':forgottenPassword')->setName('forgotten-password');
    $group->put('/forgotten-password', UserController::class.':forgottenPassword');

    $group->get('/reset-password', ViewController::class.':resetPassword')->setName('reset-password')->add(RecoveryStateOnlyMiddleware::class);
    $group->put('/reset-password', UserController::class.':resetPassword')->add(RecoveryStateOnlyMiddleware::class);

    $group->get('/recovery/{id}/{token}', UserController::class.':recovery');

    $group->get('/resend-email', ViewController::class.':resendEmail')->setName('resend-email');
    $group->post('/resend-email', UserController::class.':resendEmail');

})->add(AlreadyLoggedInMiddleware::class)->add(AuthenticatedMiddleware::class)->add(Guard::class);
// End of Routes Inaccessible When Logged In


//Routes Accessible Only When a User is Logged In
$app->group('', function(Group $group) {
    $group->get('/profile', ViewController::class.':profile')->setName('profile');
    $group->put('/profile/{user}/update-profile', UserController::class.':updateProfile')->setName('update-profile');
    $group->put('/profile/{user}/update-password', UserController::class.':updatePassword')->setName('update-password');
    $group->delete('/profile/{user}/destroy-user', UserController::class.':destroyUser')->setName('destroy-user');
    
    $group->post('/logout', UserController::class.':destroy')->setName('logout');

})->add(LoggedInOnlyMiddleware::class)->add(AuthenticatedMiddleware::class)->add(Guard::class);
//Routes Accessible Only When a User is Logged In


// Admin Routes
$app->group('/admin', function(Group $group) {
    $group->get('', ViewController::class.':adminIndex')->setName('admin.index');

    $group->get('/categories', CategoryController::class.':index')->setName('add-category');
    $group->post('/categories', CategoryController::class.':store');
    $group->get('/categories/{category}/edit', CategoryController::class.':edit')->setName('edit-category');
    $group->put('/categories/{category}', CategoryController::class.':update')->setName('alter-category');
    $group->delete('/categories/{category}', CategoryController::class.':destroy');

    $group->get('/menu', MenuController::class.':index')->setName('menu-list');
    $group->post('/menu', MenuController::class.':store');
    $group->get('/menu/create', MenuController::class.':create')->setName('add-menu');
    $group->get('/menu/{menu}/edit', MenuController::class.':edit')->setName('edit-menu');
    $group->put('/menu/{menu}', MenuController::class.':update')->setName('alter-menu');
    $group->delete('/menu/{menu}', MenuController::class.':destroy');

    $group->get('/users', ViewController::class.':users')->setName('all-users');
    $group->put('/users/{customer}/activate', UserController::class.':activate')->setName('user.activate');
    $group->put('/users/{customer}/deactivate', UserController::class.':deactivate')->setName('user.deactivate');
    $group->put('/users/{customer}/block', UserController::class.':block')->setName('user.block');
    
    $group->get('/orders', OrderController::class.':index')->setName('orders');
    $group->get('/orders/{order}', ViewController::class.':viewOrder')->setName('view-order');
    $group->put('/orders/{order}/confirm', OrderController::class.':confirm')->setName('order-confirm');
    $group->put('/orders/{order}/disapprove', OrderController::class.':disapprove')->setName('order-disapprove');
    
    $group->get('/delivery-locations', LocationController::class.':index')->setName('delivery-locations');
    $group->post('/delivery-locations', LocationController::class.':store');
    $group->get('/delivery-locations/{location}', LocationController::class.':edit')->setName('edit-delivery-location');
    $group->put('/delivery-locations/{location}', LocationController::class.':update');

})->add(AdminOnlyMiddleware::class)->add(LoggedInOnlyMiddleware::class)->add(AuthenticatedMiddleware::class)->add(Guard::class);
// End of Admin Routes

// Webhook route for online payments
$app->post('/webhook', OrderController::class.':webhook');