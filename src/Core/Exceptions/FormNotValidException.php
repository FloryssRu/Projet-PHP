<?php

namespace App\Core\Exceptions;

class FormNotValidException extends \Exception
{
    public function __construct()
    {
        echo '<p>Form is not valid or not submit.</p>';
    }
}