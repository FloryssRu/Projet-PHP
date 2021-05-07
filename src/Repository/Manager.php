<?php

namespace App\Repository;

class Manager
{
	protected string $table;
	protected $object;
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
			
	/**
	 * Retrieves the row that matches the id
	 *
	 * @param  mixed $id
	 * @return array
	 */
	public function getById($id): array
	{
		$req = $this->database->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
		$req->execute(array('id' => $id));
		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->object);
		return $req->fetch();
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
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->object);
		return $req->fetchAll();
	}
	
	/**
	 * Insert a new line in a table
	 *
	 * @param  array $params Associative array that contains the field names as keys and the values to be inserted as values
	 * @return void
	 */
	public function insert(array $params): void
	{
		foreach($params as $key => $value) {
			$fieldNames[] = $key;
			$valuesToUpdate[] = $value;
		}
		$sql = "INSERT INTO " . $this->table . "(" . implode(', ', $fieldNames) . ") VALUES (\"" . implode('", "', $valuesToUpdate) . "\")";
		$req = $this->database->query($sql);
		$req->closeCursor();
	}
	
	/**
	 * Update a line in the table targeted by its id
	 *
	 * @param  array $params Associative array that contains the field names as keys and the values to be inserted as values, for the fields to modify
	 * @param  mixed $id
	 * @return void
	 */
	public function update(array $params, $id): void
	{
		$sql = "UPDATE " . $this->table . ' SET ';

		foreach($params as $key => $value) {
			$values[] = $key . ' = "' . $value . '" ';
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