<?php

namespace App\Core\Exceptions;

class ControllerNotFoundException extends \Exception
{
    public function __constrcut()
    {
        echo '<p>No controller has been found</p>';
    }
}