<?php

namespace App\Exceptions;

class UserAlreadyExistsException extends ServiceException
{
    public function __construct()
    {
        parent::__construct('registration.user.alreadyExists', '3000');
    }
}