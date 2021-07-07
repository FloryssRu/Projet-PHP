<?php

namespace App\Repository\Manager;

use App\Repository\Manager;

class CommentManager extends Manager
{
	private const SELECT_ALL_FROM = "SELECT * FROM ";

	public function __construct($object)
	{
        parent::__construct("comment", $object);
	}

    public function getCommentNotValidated()
    {
        $req = $this->database->prepare(
			self::SELECT_ALL_FROM
			. $this->table
			. " WHERE is_validated = 0"
		);
		$req->execute();
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		return $req->fetchAll();
    }

	public function getCommentValidated()
    {
        $req = $this->database->prepare(
			self::SELECT_ALL_FROM
			. $this->table
			. " WHERE is_validated = 1"
		);
		$req->execute();
		$req->setFetchMode(\PDO::FETCH_CLASS, parent::PATH_TO_ENTITIES . $this->object);
		return $req->fetchAll();
    }

	public function getCommentsByIdPost(int $idPost)
	{
		$req = $this->database->prepare(
			self::SELECT_ALL_FROM
			. $this->table
			. " WHERE id_post = "
			. $idPost
			. " AND is_validated = 1 ORDER BY date DESC"
		);
		$req->execute();
		return $this->finishQuery($req);
	}

	/**
	 * Return an array with all the comments of an user. This is used for dashboard.
	 *
	 * @param  string $pseudo
	 * @return ?array
	 */
	public function getAllCommentsByPseudo(string $pseudo)
	{
		$req = $this->database->prepare(
			self::SELECT_ALL_FROM
			. $this->table
			. " WHERE pseudo = \""
			. $pseudo
			. "\" ORDER BY date DESC"
		);
		$req->execute();
		return $this->finishQuery($req);
	}

	/**
	 * Get the comments with the related avatars of a post
	 *
	 * @return array
	 */
	public function getAllCommentsWithAvatars(int $idPost)
	{
		$req = $this->database->prepare(
			"SELECT comment.*, user.avatar_number as avatarNumber
			FROM comment INNER JOIN user ON user.pseudo = comment.pseudo
			WHERE comment.id_post= :idPost AND comment.is_validated = 1
			ORDER BY date DESC"
		);
		$req->execute(['idPost' => $idPost]);
		return $this->finishQuery($req);
	}

	/**
	 * Get the comments of one user defined by his pseudo. Each comment has the title of the post on which it is published
	 *
	 * @param  string $pseudo
	 * @return array
	 */
	public function getUserCommentsWithPostTitle(string $pseudo)
	{
		$req = $this->database->prepare(
			"SELECT comment.*, post.title FROM comment
			INNER JOIN post ON comment.id_post = post.id
			WHERE comment.pseudo = \""
			. $pseudo
			. "\" ORDER BY date DESC"
		);
		$req->execute();
		return $this->finishQuery($req);
	}
}