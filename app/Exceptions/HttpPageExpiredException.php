<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpPageExpiredException extends HttpSpecializedException
{
    
    protected $code = 419;
    protected $message = 'Page Expired.';
    protected $title = '419 Page Expired';
    protected $description = 'You cannot access this page because the user session has expired.';

}
