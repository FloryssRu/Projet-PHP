<?php

//file for example

namespace App\Repository\Manager;

use App\Repository\DatabaseManager;

class UserManager extends DatabaseManager
{
	public function __construct($datasource)
	{
		parent::__construct("user", "User", $datasource);	
	}

    public function getByMail($mail)
	{
		$req = $this->_bdd->prepare("SELECT * FROM user
                                     WHERE mail = :mail");
		$req->execute(array('mail' => $mail));
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE,"User");
		return $req->fetch();
	}
}