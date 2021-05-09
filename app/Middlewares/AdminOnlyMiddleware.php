<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;
use App\Models\User;

class AdminOnlyMiddleware implements MiddlewareInterface
{

    public function process(Request $request, Handler $handler) : ResponseInterface
    {
        // If User is logged in
        if (isset($_SESSION['id'])) {
            $user = User::find($_SESSION['id']);

            // This should never run unless a user tries to act smart
            if ($user === null) {
                throw new HttpNotFoundException($request);

                return $handler->handle($request);
            }
            
            // Regular users, sorry but you are not allowed access
            if ($user->role !== 2) {
                throw new HttpForbiddenException($request);

                return $handler->handle($request);
            }

            // Welcome admin, you have access
            if ($user->role === 2) {
                $request = $request->withAttribute('is_admin', true);

                return $handler->handle($request);
            }

            return $handler->handle($request);
        }

        return $handler->handle($request);
    }

}