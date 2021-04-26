<?php

namespace App\Repository\Manager;

use App\Repository\Manager;
use App\Entity\Post;

class AddPostManager extends Manager
{
	public function __construct($object)
	{
        parent::__construct("post", $object);
	}

    public function savePost(Post $post): void
    {
        $sth = $this->database->prepare('INSERT INTO post(title, heading, content, author, date_publication, date_last_update) VALUES (:title, :heading, :content, :author, :publication, :last_update)');
            $array = array('title' => $post->getTitle(),
                           'heading' => $post->getHeading(),
                           'content' => $post->getContent(),
                           'author' => $post->getAuthor(),
                           'publication' => $post->getDatePublication(),
                           'last_update' => $post->getDateLastUpdate());
            $sth->execute($array);
        $sth->closeCursor();        

        
                       
    }
}