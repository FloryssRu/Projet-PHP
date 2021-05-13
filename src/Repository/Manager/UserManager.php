<?php

namespace App\Repository\Manager;

use App\Repository\Manager;

class UserManager extends Manager
{
	public function __construct($object)
	{
        parent::__construct("user", $object);
	}
    
    /**
     * Get pseudo of a user with his id
     *
     * @param  int $idUser
     * @return mixed
     */
    public function getPseudoByIdUser(int $idUser)
    {
        $req = $this->database->query("SELECT * FROM " . $this->table . " WHERE id = " . $idUser);
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->object);
		return $req->fetchAll();
    }
	
	/**
	 * Test if a match exist with the login and the password send
	 *
	 * @param  string $pseudo
	 * @param  string $password
	 * @return mixed idUser if a match has been found
	 */
	public function findOneUserBy(string $pseudo, string $password)
	{
		$req = $this->database->query("SELECT id FROM " . $this->table . " WHERE pseudo = '" . $pseudo . "'");
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->object);
		$idUser = $req->fetch()['id'];

		$req = $this->database->query("SELECT password FROM " . $this->table . " WHERE id = '" . $idUser . "'");
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->object);
		$passwordHashed = $req->fetch()['password'];

		if(password_verify($password, $passwordHashed))
		{
			return $pseudo;
		}
		
	}

	
	/**
	 * Test if an email is in the database. Return false if it's not, else true.
	 *
	 * @param  string $email Email to test
	 * @return bool
	 */
	public function getEmail(string $email): bool
	{
		$req = $this->database->query("SELECT email FROM " . $this->table . " WHERE email = " . $email);
		if($req == false)
		{
			return false;
		} else
		{
			return true;
		}
	}
	
	/**
	 * Find a user id from a uuid
	 *
	 * @param  string $uuid
	 * @return mixed
	 */
	public function getidByUuid(string $uuid)
	{
		$req = $this->database->query("SELECT id FROM " . $this->table . " WHERE uuid = '" . $uuid . "'");
		
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->object);
		$arrayResults = $req->fetch();
		if($arrayResults)
		{
			return $arrayResults['id'];
		}
	}
	
	/**
	 * Used to find the user's id from their email address. (password reset)
	 *
	 * @param  string $email
	 * @return mixed
	 */
	public function getIdByEmail(string $email)
	{
		$req = $this->database->query("SELECT id FROM " . $this->table . " WHERE email = '" . $email . "'");
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->object);
		$idUser = $req->fetch();
		if($req != false)
		{
			return $idUser;
		}
	}
	
	/**
	 * Delete the uuid of a user
	 *
	 * @param  int $idUser
	 * @return void
	 */
	public function updateUuid(int $idUser): void
	{
		$this->database->query("UPDATE " . $this->table . " SET uuid = NULL WHERE id = " . $idUser);
	}
    
}