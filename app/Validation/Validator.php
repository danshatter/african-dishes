<?php

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;

class Validator
{

    private $errors = [];

    public function __construct()
    {
        Factory::setDefaultInstance((new Factory)->
                withRuleNamespace('App\\Validation\\Rules')->
                withExceptionNamespace('App\\Validation\\Exceptions'));
    }

    public function validate(array $data, array $rules)
    {
        $info = [];

        foreach ($rules as $field => $rule) {
            try {
                $rule->assert($data[$field]);
                $info[$field] = $data[$field];
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages([
                    'alpha' => $this->errorReady($field).' must contain only letters "(a-z)"',
                    'noWhitespace' => $this->errorReady($field).' must not contain white spaces',
                    'alnum' => $this->errorReady($field).' must contain only letters "(a-z)", digits "(0-9)"',
                    'email' => $this->errorReady($field).' must be an valid email',
                    'equals' => $this->errorReady($field).' must equal '.$this->errorReady('password'),
                    'notEmpty' => $this->errorReady($field).' is required',
                    'length' => $this->errorReady($field).' length must be greater than or equal to {{minValue}}',
                    'phone' => $this->errorReady($field).' must be a valid phone number'
                ]);
            }
        }

        return $info;
    }

    public function ready(array &$data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'password' || $key === 'password_again') {
                continue;
            }

            if (trim($value) === "") {
                $data[$key] = null;
            } else {
                $data[$key] = trim($value);
            }
        }

        return $this;
    }

    public function failed()
    {
        return count($this->errors) !== 0;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function errorReady($field)
    {
        $var = str_replace('_', ' ', $field);
        return ucfirst($var);
    }
}