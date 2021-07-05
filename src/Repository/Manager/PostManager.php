<?php

namespace App\Repository\Manager;

use App\Repository\Manager;

class PostManager extends Manager
{
	private const WHERE_SLUG = " WHERE slug = :slug";

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
		$req = $this->database->prepare(
			"SELECT slug FROM " . $this->table . self::WHERE_SLUG
		);
		$req->execute(['slug' => $slug]);
		$req->setFetchMode(\PDO::FETCH_BOUND);
		return $req->fetch();
	}

	public function titleIsFree(string $title): bool
	{
		$req = $this->database->prepare(
			"SELECT title FROM " . $this->table . " WHERE title = :title"
		);
		$req->execute(['title' => $title]);
		$req->setFetchMode(\PDO::FETCH_BOUND);
		return $req->fetch();
	}

	/**
	 * Retrieves the id that matches the slug
	 *
	 * @param  string $slug
	 * @return int
	 */
	public function getIdBySlug(string $slug): int
	{
		$req = $this->database->prepare(
			"SELECT id FROM " . $this->table . self::WHERE_SLUG
		);
		$req->execute(['slug' => $slug]);
		return $req->fetch()['id'];
	}

	public function getBySlug(string $slug): Object
	{
		$req = $this->database->prepare(
			"SELECT * FROM " . $this->table . self::WHERE_SLUG
		);
		$req->execute(['slug' => $slug]);
		$req->setFetchMode(\PDO::FETCH_CLASS, self::PATH_TO_ENTITIES . $this->object);
		$objectResult = $req->fetch();

		foreach ($objectResult as $attribute => $value) {
			if (preg_match('#^[a-z]+(_[a-z]+)+$#', $attribute)) {
				$method = 'set' . preg_replace('#_#', '', ucwords($attribute, '_'));

        		if (method_exists($objectResult, $method)) {
        		    $objectResult->$method($value);
        		}
				unset($objectResult->$attribute);
			}
		}
		return $objectResult;
	}
}