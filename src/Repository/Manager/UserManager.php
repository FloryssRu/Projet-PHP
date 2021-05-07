<?php

namespace App\Repository\Manager;

use App\Repository\Manager;

class UserManager extends Manager
{
	public function __construct($object)
	{
        parent::__construct("user", $object);
	}

    public function getPseudoByIdUser($idUser)
    {
        $req = $this->database->prepare("SELECT * FROM " . $this->table . " WHERE id = " . $idUser);
		$req->execute();
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
		$arrayLogins = $this->getLogins();
		$numberOfUsers = count($arrayLogins) - 1;
		for($i = 0; $i <= $numberOfUsers; $i++)
		{
			//problème avec password_verify qui dit que c'est pas bon
			if($arrayLogins[$i]['pseudo'] == $pseudo && password_verify($password, $arrayLogins[$i]['password']))
			{
				return $arrayLogins[$i]['id'];
			}
		}
	}

	private function getLogins()
	{
		$req = $this->database->query("SELECT id, pseudo, password FROM " . $this->table);
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->object);
		return $req->fetchAll();
	}
    
}