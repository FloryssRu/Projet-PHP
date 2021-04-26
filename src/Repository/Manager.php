<?php

namespace App\Repository;

use App\Core\Exceptions\PropertyNotFoundException;

class Manager
{
	private string $table;
	private object $object;
	protected object $database;
		
	public function __construct($table, $object)
	{
		$this->table = $table;
		$this->object = $object;
		$configFile = file_get_contents("../config/config.json");
		$config = json_decode($configFile);
		$datasource = $config->database;
		$this->database = Database::getInstance($datasource);
	}
		
	public function getById($id): string
	{
		$req = $this->database->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
		$req->execute(array('id' => $id));
		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->object);
		return $req->fetch();
	}
		
	public function getAll(): array
	{
		$req = $this->database->prepare("SELECT * FROM " . $this->table);
		$req->execute();
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->object);
		return $req->fetchAll();
	}
		
	public function insert($object, $params)
	{		
		$paramNumber = count($params);
		$valueArray = array_fill(1, $paramNumber, "?");
		$valueString = implode($valueArray . ", ");
		$sql = "INSERT INTO " . $this->table . "(" . implode($params . ", ") . ") VALUES(" . $valueString . ")";
		$req = $this->database->prepare($sql);
		$boundParam = array();
		foreach($params as $paramName)
		{
			$boundParam[$paramName] = $object->$paramName;	
		}
		$req->execute($params);
	}
		
	public function update($object, $params)
	{
		$sql = "UPDATE " . $this->_table . " SET ";
		foreach($params as $paramName)
		{
			$sql = $sql . $paramName . " = ?, ";
		}
		$sql = $sql . " WHERE id = ? ";
		$req = $this->database->prepare($sql);
		$param[] = 'id';
		$boundParam = array();
		foreach($params as $paramName)
		{
			if(property_exists($object, $paramName))
			{
				$boundParam[$paramName] = $object->$paramName;	
			}
			else
			{
				throw new PropertyNotFoundException($this->object, $paramName);	
			}
		}
		
		$req->execute($boundParam);
	}
		
	public function delete($object): bool
	{
		if(property_exists($object,"id"))
		{
			$req = $this->database->prepare("DELETE FROM " . $this->table . " WHERE id=?");
			return $req->execute(array($object->id));
		}
		else
		{
			throw new PropertyNotFoundException($this->object, "id");
		}
	}
}