<?php

namespace App\Repository\Manager;

use App\Repository\DatabaseManager;
use App\Entity\Post;

class AddPostManager extends DatabaseManager
{
	public function __construct($object)
	{
        parent::__construct("post", $object);
	}

    public function savePost(Post $post): void
    {
        //var_dump($post);
        //var_dump($this->database);
        $sth = $this->database->prepare('INSERT INTO post(title, heading, content, author, date_publication, date_last_update) VALUES (:title, :heading, :content, :author, :publication, :last_update)');
        var_dump($sth);
            $array = array('title' => $post->getTitle(),
                           'heading' => $post->getHeading(),
                           'content' => $post->getContent(),
                           'author' => $post->getAuthor(),
                           'publication' => $post->getDatePublication(),
                           'last_update' => $post->getDateLastUpdate());
            $sth->execute($array);
            //$sth->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, "User");
        $sth->closeCursor();        

        
                       
    }
    
    public function getPost(Post $post)
	{
		$sth = $this->database->prepare('SELECT * FROM post');
            $array = array('title' => $post->getTitle(),
                           'heading' => $post->getHeading(),
                           'content' => $post->getContent(),
                           'author' => $post->getAuthor());
            $sth->execute($array);
            while ($row = $sth->fetch()) {
                //ici requete
            }
            $sth->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, "User");
        $sth->closeCursor();
	}
}