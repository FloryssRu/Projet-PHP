<?php

namespace App\Repository\Manager;

use App\Repository\Manager;

class ContactManager extends Manager
{
    public function __construct($object)
	{
        parent::__construct("contact", $object);
	}

    public function changeStatutMessage(int $id, bool $isProcessed)
    {
        $req = $this->database->prepare("UPDATE " . $this->table . " SET is_processed = :isProcessed  WHERE id = :id");
		$req->execute(array('id' => $id, 'isProcessed' => $isProcessed));
		return true;
    }
}