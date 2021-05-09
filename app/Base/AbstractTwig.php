<?php

namespace App\Base;

use Twig\Extension\AbstractExtension;
use Slim\Flash\Messages as Flash;
use Slim\Csrf\Guard as Csrf;

abstract class AbstractTwig extends AbstractExtension
{

    protected $old;
    protected $errors;
    protected $flash;
    protected $csrf;

    public function __construct(Flash $flash, Csrf $csrf)
    {
        $this->flash = $flash;
        $this->csrf = $csrf;
    }

    public function getOldInput()
    {
        if (isset($_SESSION['old'])) {
            $this->old = $_SESSION['old'];
            
            unset($_SESSION['old']);
            
            return $this->old;
        }
    }

    public function getErrors()
    {
        if (isset($_SESSION['errors'])) {
            $this->errors = $_SESSION['errors'];
            
            unset($_SESSION['errors']);
            
            return $this->errors;
        }
    }

    public function getFlash()
    {
        return $this->flash;
    }

    public function getCsrf()
    {
        return '<input type="hidden" name="'.$this->csrf->getTokenNameKey().'" value="'.$this->csrf->getTokenName().'" />
        <input type="hidden" name="'.$this->csrf->getTokenValueKey().'" value="'.$this->csrf->getTokenValue().'" />';
    }

}