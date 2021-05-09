<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpLockedException extends HttpSpecializedException
{
    protected $code = 423;
    protected $message = 'Locked.';
    protected $title = '423 Locked';
    protected $description = 'This resource cannot be accessed as you have been blocked or deactivated by the Administrator.';
}
