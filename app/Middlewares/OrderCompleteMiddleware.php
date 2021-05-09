<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Exception\HttpNotFoundException;

class OrderCompleteMiddleware implements MiddlewareInterface
{

    public function process(Request $request, Handler $handler) : ResponseInterface
    {
        // This middleware is used for pay on delivery
        if (isset($_SESSION['order_id'])) {
            $request = $request->withAttribute('order-id', $_SESSION['order_id']);
        }

        return $handler->handle($request);
    }
}