<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @license   https://github.com/slimphp/Twig-View/blob/master/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace App\Base;

use Psr\Http\Message\UriInterface;
use Slim\Interfaces\RouteParserInterface;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

class TwigRuntimeLoader implements RuntimeLoaderInterface
{

    public function load(string $class)
    {
        return new TwigRuntimeExtension();
    }

}
