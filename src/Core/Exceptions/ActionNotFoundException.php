<?php

namespace App\Core\Exceptions;

class ActionNotFoundException extends \Exception
{
    public function __construct()
    {
        echo '<p>Problem with Action</p>';
    }
}