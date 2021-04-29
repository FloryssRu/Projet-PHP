<?php

namespace App\Repository\Manager;

use App\Repository\Manager;
use App\Entity\Post;

class AddPostManager extends Manager
{
	public function __construct($object)
	{
        parent::__construct("post", $object);
	}
}