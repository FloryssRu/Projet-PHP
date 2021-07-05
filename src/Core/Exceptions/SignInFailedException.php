<?php

namespace App\Core\Exceptions;

class SignInFailedException extends \Exception
{
    private string $error;

    public function __construct(string $error)
    {
	    $this->error = $error;
    }

    public function getMoreDetail()
    {
        return $this->error;
    }
}