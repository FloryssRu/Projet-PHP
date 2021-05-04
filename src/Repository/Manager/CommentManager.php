<?php

namespace App\Repository\Manager;

use App\Repository\Manager;

class CommentManager extends Manager
{
	public function __construct($object)
	{
        parent::__construct("comment", $object);
	}

    public function getCommentNotValidated()
    {
        $req = $this->database->prepare("SELECT * FROM " . $this->table . " WHERE is_validated = 0");
		$req->execute();
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->object);
		return $req->fetchAll();
    }

	public function getCommentsByIdPost(int $idPost)
	{
		$req = $this->database->prepare("SELECT * FROM " . $this->table . " WHERE id_post = " . $idPost . " AND is_validated = 1 ORDER BY date DESC");
		$req->execute();
		$req->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->object);
		return $req->fetchAll();
	}
    
}