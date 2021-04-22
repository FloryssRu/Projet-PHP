<?php

namespace App\Repository;

use PDO;

class Database extends PDO
{
	private object $database; //si on enlève "object" ça fonctionne mais ça ne fait pas les requêtes
	private static object $instance;
	
	private function __construct(object $datasource)
	{
		//var_dump($datasource);
		$this->database = new PDO('mysql:dbname=' . $datasource->dbname . ';host=' . $datasource->host,
							  					 $datasource->user,
												 $datasource->password);
		//var_dump($this->database);
	}

	public static function getInstance($datasource)
	{
		if(empty(self::$instance))
		{
			self::$instance = new Database($datasource);
			//var_dump(self::$instance);
		}
		return self::$instance->database;
	}
	
	public function getDatabase(): object
	{
		return $this->database;
	}
}