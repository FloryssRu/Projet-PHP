<?php

namespace App\Repository\Manager;

use App\Repository\Manager;

class UserManager extends Manager
{
	private const SELECT_ID_FROM = "SELECT id FROM ";
	private const WHERE_PSEUDO = " WHERE pseudo = :pseudo";

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
        $req = $this->database->prepare(
			"SELECT * FROM " . $this->table . " WHERE id = :idUser"
		);
		$req->execute(['idUser' => $idUser]);
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		return $req->fetchAll();
    }

	/**
	 * Test if a match exist with the login and the password send
	 *
	 * @param  string $pseudo
	 * @param  string $password
	 * @return object $user if a match has been found
	 */
	public function findOneUserBy(string $pseudo, string $password)
	{
		$req = $this->database->prepare(
			"SELECT id, password FROM " . $this->table . self::WHERE_PSEUDO
		);
		$req->execute(["pseudo" => $pseudo]);
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		$user = $req->fetch();

		if (password_verify($password, $user->getPassword())) {
			return $user;
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
		$req = $this->database->prepare(
			"SELECT email FROM " . $this->table . " WHERE email = :email"
		);
		$req->execute(["email" => $email]);
		return $req->fetch();
	}

	/**
	 * Test if a pseudo is in the database. Return false if it's not, else true.
	 *
	 * @param  string $email Email to test
	 */
	public function getPseudo(string $pseudo)
	{
		$req = $this->database->prepare(
			"SELECT pseudo FROM " . $this->table . self::WHERE_PSEUDO
		);
		$req->execute(['pseudo' => $pseudo]);
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
		$req = $this->database->prepare(
			self::SELECT_ID_FROM . $this->table . " WHERE uuid = :uuid"
		);
		$req->execute(['uuid' => $uuid]);
		
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		$user = $req->fetch();
		if ($user) {
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
		$req = $this->database->prepare(
			self::SELECT_ID_FROM . $this->table . " WHERE email = :email"
		);
		$req->execute(['email' => $email]);
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		$user = $req->fetch();
		if (!is_bool($req)) {
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
		$req = $this->database->prepare(
			"SELECT admin FROM " . $this->table . " WHERE id = :idUser"
		);
		$req->execute(['idUser' => $idUser]);
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		$user = $req->fetch();
		if ($user->getAdmin() == 1) {
			return true;
		} 
		return false;
	}

	public function getAdminsEmails()
    {
        $req = $this->database->query(
			"SELECT email FROM " . $this->table . " WHERE admin = 1"
		);
		return $req->fetchAll(\PDO::FETCH_COLUMN);
    }

	public function getAvatarByPseudo(string $pseudo)
	{
		$req = $this->database->prepare(
			"SELECT avatar_number FROM "
			. $this->table
			. self::WHERE_PSEUDO
		);
		$req->execute(['pseudo' => $pseudo]);
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		return $req->fetch();
	}
}