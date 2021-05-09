<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Base\{TwigRuntimeLoader, TwigRuntimeExtension, TwigExtension};
use Slim\Flash\Messages as Flash;
use Slim\Csrf\Guard as Csrf;
use Slim\Views\Twig as View;

class TwigMiddleware implements MiddlewareInterface
{

    public function __construct(Flash $flash, Csrf $csrf, View $view)
    {
        $this->flash = $flash;
        $this->csrf = $csrf;
        $this->view = $view;
    }

    public function process(Request $request, Handler $handler) : ResponseInterface
    {
        // Add Runtime Loader for the runtime twig extension class
        $runtimeLoader = new TwigRuntimeLoader; 
        $this->view->addRuntimeLoader($runtimeLoader);

        // Add the Twig extension
        $extension = new TwigExtension($this->flash, $this->csrf);
        $this->view->addExtension($extension);
        
        return $handler->handle($request);
    }
}