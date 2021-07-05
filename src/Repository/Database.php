<?php

namespace App\Repository;

use PDO;

class Database extends PDO
{
	private object $database;
	private static object $instance;
	
	private function __construct(object $datasource)
	{
		$this->database = new PDO(
			'mysql:dbname=' . $datasource->dbname
			. ';host=' . $datasource->host, $datasource->user,
			$datasource->password
		);
	}

	public static function getInstance($datasource)
	{
		if (empty(self::$instance)) {
			self::$instance = new Database($datasource);
		}
		return self::$instance->database;
	}
	
	public function getDatabase(): object
	{
		return $this->database;
	}
}