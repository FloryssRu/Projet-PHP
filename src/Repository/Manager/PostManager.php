<?php

namespace App\Repository\Manager;

use App\Repository\Manager;

class PostManager extends Manager
{
	public function __construct($object)
	{
        parent::__construct("post", $object);
	}
	
	/**
	 * Search the slug given in database and check if it exists
	 *
	 * @param  mixed $slug
	 * @return bool
	 */
	public function thisSlugExists(string $slug): bool
	{
		$req = $this->database->prepare("SELECT slug FROM " . $this->table . " WHERE slug = :slug");
		$req->execute(array('slug' => $slug));
		$req->setFetchMode(\PDO::FETCH_BOUND);
		return $req->fetch();
	}
}