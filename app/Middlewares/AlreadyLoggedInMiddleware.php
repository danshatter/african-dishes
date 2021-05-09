<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Routing\RouteContext;
use App\Models\User;

class AlreadyLoggedInMiddleware implements MiddlewareInterface
{

    public function __construct(RouteParser $routeParser)
    {
        $this->routeParser = $routeParser;
    }

    public function process(Request $request, Handler $handler) : ResponseInterface
    {
        $name = RouteContext::fromRequest($request)->getRoute()->getName();
        $response = $handler->handle($request);
        
        if (isset($_SESSION['id'])) {
            $user = User::find($_SESSION['id']);

            if ($user !== null) {
                // URL to go to when redirected because of absence of authentication
                if (isset($_SESSION['next'])) {
                    $next = $_SESSION['next'];

                    unset($_SESSION['next']);

                    return $response->withHeader('Location', $next);
                }

                if ($user->role === 1) {
                    return $response->withHeader('Location', $this->routeParser->urlFor('profile'));
                } elseif ($user->role === 2) {
                    return $response->withHeader('Location', $this->routeParser->urlFor('admin.index'));
                }
            } 
        }
        
        return $response;
    }

}