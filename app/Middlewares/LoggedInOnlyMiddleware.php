<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Flash\Messages as Flash;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Routing\RouteContext;
use App\Models\User;
use App\Exceptions\HttpLockedException;

class LoggedInOnlyMiddleware implements MiddlewareInterface {

    private $flash;
    private $routeParser;

    public function __construct(Flash $flash, RouteParser $routeParser)
    {
        $this->flash = $flash;
        $this->routeParser = $routeParser;
    }

    public function process(Request $request, Handler $handler) : ResponseInterface
    {
        $name = RouteContext::fromRequest($request)->getRoute()->getName();
        $response = $handler->handle($request);
        
        if (!isset($_SESSION['id'])) {
            if ($name === 'logout' || $name === 'destroy-user') {
                return $response->withHeader('Location', $this->routeParser->urlFor('home'));
            }

            $this->flash->addMessage('not-logged-in', 'You must be logged in to view that page');

            $_SESSION['next'] = $request->getRequestTarget();

            return $response->withHeader('Location', $this->routeParser->urlFor('sign-in'));
        } else {
            $user = User::find($_SESSION['id']);

            if ($user->role === 1 && $user->status['status'] !== 'active') {
                throw new HttpLockedException($request);

                return $response;
            }

            return $response;
        }
    }
}