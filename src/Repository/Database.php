<?php

namespace App\Repository;

class Database
{
	private object $database;
	private static object $instance;
	
	private function __construct($datasource)
	{
		$this->_bdd = new \PDO('mysql:dbname=' . $datasource->database->dbname . ';host=' . $datasource->host,
							  					 $datasource->database->user,
												 $datasource->database->password);
	}

	public static function getInstance($datasource): object
	{
		if(empty(self::$instance))
		{
			self::$instance = new Database($datasource);
		}
		return self::$instance->database;
	}
	
	public function getDatabase(): object
	{
		return $this->database;
	}
}