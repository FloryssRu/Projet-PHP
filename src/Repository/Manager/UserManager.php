<?php

namespace App\Repository\Manager;

use App\Repository\Manager;

class UserManager extends Manager
{

	private const SELECT_ID_FROM = "SELECT id FROM ";

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
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
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
		$req = $this->database->query("SELECT id, password FROM " . $this->table . " WHERE pseudo = '" . $pseudo . "'");
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		$user = $req->fetch();

		if(password_verify($password, $user->getPassword()))
		{
			return $user->getId();
		}
	}
	
	/**
	 * Test if an email is in the database. Return false if it's not, else true.
	 *
	 * @param  string $email Email to test
	 * @return mixed
	 */
	public function getEmail(string $email)
	{
		$req = $this->database->query("SELECT email FROM " . $this->table . " WHERE email = \"" . $email . "\"");
		return $req->fetch();
	}

	/**
	 * Test if a pseudo is in the database. Return false if it's not, else true.
	 *
	 * @param  string $email Email to test
	 * @return bool
	 */
	public function getPseudo(string $pseudo): bool
	{
		$req = $this->database->query("SELECT pseudo FROM " . $this->table . " WHERE pseudo = \"" . $pseudo . "\"");
		return $req->fetch();
	}
	
	/**
	 * Find a user id from a uuid
	 *
	 * @param  string $uuid
	 * @return mixed
	 */
	public function getidByUuid(string $uuid)
	{
		$req = $this->database->query(self::SELECT_ID_FROM . $this->table . " WHERE uuid = '" . $uuid . "'");
		
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		$user = $req->fetch();
		if($user)
		{
			return $user->getId();
		}
	}
	
	/**
	 * Find the user's id from their email address. (password reset)
	 *
	 * @param  string $email
	 * @return mixed
	 */
	public function getIdByEmail(string $email)
	{
		$req = $this->database->query(self::SELECT_ID_FROM . $this->table . " WHERE email = '" . $email . "'");
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		$user = $req->fetch();
		var_dump($user);
		if(!is_bool($req))
		{
			return $user;
		}
	}
	
	/**
	 * Return if a user is an admin (true if yes, false if no)
	 *
	 * @param  int $idUser
	 * @return bool
	 */
	public function isAdminById(int $idUser): bool
	{
		$req = $this->database->query("SELECT admin FROM " . $this->table . " WHERE id = '" . $idUser . "'");
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		$user = $req->fetch();
		if($user->getAdmin() == 1)
		{
			return true;
		} 
		return false;
	}    
}