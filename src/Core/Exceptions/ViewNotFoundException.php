<?php

namespace App\Core\Exceptions;

class ViewNotFoundException extends \Exception
{
    public function __constrcut()
    {
        echo '<p>No template has been found</p>';
    }
}