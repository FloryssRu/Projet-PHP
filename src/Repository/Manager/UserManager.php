<?php

namespace App\Repository\Manager;

use App\Repository\Manager;

class UserManager extends Manager
{
	public function __construct($object)
	{
        parent::__construct("user", $object);
	}

    public function getPseudo($id)
    {

    }
    
}