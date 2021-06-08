<?php

namespace App\Repository;

class Manager
{
	protected string $table;
	protected $object;
	protected object $database;
	protected const PATH_TO_ENTITIES = 'App\Entity\\';
		
	public function __construct($table, $object)
	{
		$this->table = $table;
		$this->object = $object;
		$configFile = file_get_contents("../config/config.json");
		$config = json_decode($configFile);
		$datasource = $config->database;
		$this->database = Database::getInstance($datasource);
	}
			
	/**
	 * Retrieves the row that matches the id
	 *
	 * @param  mixed $id
	 * @return Object
	 */
	public function getById($id): Object
	{
		$req = $this->database->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
		$req->execute(array('id' => $id));
		$req->setFetchMode(\PDO::FETCH_CLASS, self::PATH_TO_ENTITIES . $this->object);
		return $req->fetch();
	}

	/**
	 * Retrieves the row that matches the slug
	 *
	 * @param  string $slug
	 * @return int
	 */
	public function getIdBySlug($slug): int
	{
		$req = $this->database->prepare("SELECT id FROM " . $this->table . " WHERE slug = :slug");
		$req->execute(array('slug' => $slug));
		return $req->fetch()['id'];
	}
			
	/**
	 * Retrieves all the row that are in the table
	 *
	 * @return array
	 */
	public function getAll(): array
	{
		$req = $this->database->prepare("SELECT * FROM " . $this->table);
		$req->execute();
		$req->setFetchMode(\PDO::FETCH_CLASS, self::PATH_TO_ENTITIES . $this->object, []);
		return $req->fetchAll();
	}
	
	/**
	 * Insert a new line in a table
	 *
	 * @param  object $params Associative array that contains the field names as keys and the values to be inserted as values
	 * @return void
	 */
	public function insert(array $arrayData): void
	{
		foreach($arrayData as $key => $value) {
			$key = preg_replace('/(?=[A-Z])/', '_', $key);
			$key = strtolower($key);
			$fieldNames[] = $key;

			if($value === NULL)
			{
				$valuesToInsert[] = 'NULL';
			} else
			{
				if(is_string($value))
				{
					$value = addslashes($value);
					$valuesToInsert[] = '"' . $value . '"';
				} else
				{
					$valuesToInsert[] = $value;
				}
			}
		}
		$this->database->query("INSERT INTO " . $this->table . "(" . implode(', ', $fieldNames) . ") VALUES (" . implode(', ', $valuesToInsert) . ")");
	}
	
	/**
	 * Update a line in the table targeted by its id
	 *
	 * @param  array $arrayData Array that contains the updated values
	 * @param  mixed $id
	 * @return void
	 */
	public function update(array $arrayData, $id): void
	{
		$sql = "UPDATE " . $this->table . ' SET ';

		foreach($arrayData as $key => $value) {
			$key = preg_replace('/(?=[A-Z])/', '_', $key);
			$key = strtolower($key);
			if($value == NULL)
			{
				$values[] = $key . ' = NULL ';
			} else
			{
				$values[] = $key . ' = "' . $value . '" ';
			}
		}

		$sql .= implode(', ', $values);

		$sql .= 'WHERE id = ' . $id;
		$req = $this->database->query($sql);
		$req->closeCursor();
	}

	/**
	 * Delete a line targeted by its id
	 *
	 * @param  mixed $id
	 * @return void
	 */
	public function delete($id)
	{
		$req = $this->database->query("DELETE FROM " . $this->table . " WHERE id=" . $id);
		$req->closeCursor();
	}
	
}