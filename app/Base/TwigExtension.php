<?php

namespace App\Base;

use Twig\Extension\GlobalsInterface;
use App\Base\AbstractTwig;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractTwig implements GlobalsInterface
{

    public function getGlobals() : array
    {
        return [
            'old' => $this->getOldInput(),
            'errors' => $this->getErrors(),
            'flash' => $this->getFlash(),
            'csrf' => $this->getCsrf()
        ];
    }

    public function getFilters() : array
    {
        return [
            new TwigFilter('ucwords', 'ucwords'),
            new TwigFilter('ucfirst', 'ucfirst')
        ];
    }

    public function getFunctions() : array
    {
        return [
            new TwigFunction('number_format', 'number_format'),
            new TwigFunction('int_price', [TwigRuntimeExtension::class, 'intPrice']),
            new TwigFunction('diff_for_humans', [TwigRuntimeExtension::class, 'diffForHumans'])
        ];
    }

}