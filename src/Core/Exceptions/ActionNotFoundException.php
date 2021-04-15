<?php

namespace App\Core\Exceptions;

class ActionNotFoundException extends \Exception
{
    public function __constrcut()
    {
        echo '<p>Problem with Action</p>';
    }
}