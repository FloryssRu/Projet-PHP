<?php

namespace App\Repository\Manager;

use App\Repository\Manager;

class AdminPostsManager extends Manager
{
	public function __construct($object)
	{
        parent::__construct("post", $object);
	}

   
}