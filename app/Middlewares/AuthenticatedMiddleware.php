<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class AuthenticatedMiddleware implements MiddlewareInterface
{

    public function process(Request $request, Handler $handler) : ResponseInterface
    {
        if (isset($_SESSION['id'])) {
            $request = $request->withAttribute('session_id', $_SESSION['id']);
            
            return $handler->handle($request);
        }

        return $handler->handle($request);
    }
}