<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class GetCartMiddleware implements MiddlewareInterface
{

    public function process(Request $request, Handler $handler) : ResponseInterface
    {
        if (isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            
            // unset cart session
            unset($_SESSION['cart']);

            $request = $request->withAttribute('cart', $cart);
                    
            return $handler->handle($request);
        }
        
        return $handler->handle($request);
    }
}