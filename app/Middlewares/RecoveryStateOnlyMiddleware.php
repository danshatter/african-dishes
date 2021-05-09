<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Exception\HttpNotFoundException;
use App\Models\User;

class RecoveryStateOnlyMiddleware implements MiddlewareInterface
{

    public function __construct(RouteParser $routeParser)
    {
        $this->routeParser = $routeParser;
    }

    public function process(Request $request, Handler $handler) : ResponseInterface
    {
        // Collect user data
        if (isset($_SESSION['recovery']) && isset($_SESSION['user_data'])) {
            $request = $request->withAttribute('recovery', $_SESSION['recovery'])
                                ->withAttribute('user_data', $_SESSION['user_data']);
            
            return $handler->handle($request);
        }

        throw new HttpNotFoundException($request);

        return $handler->handle($request);
    }

}