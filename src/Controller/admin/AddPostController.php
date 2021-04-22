<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Post;
use App\Repository\Manager\AddPostManager;
use App\Repository\Datasource;
use App\Repository\Database;

class AddPostController extends BaseController
{    
    /**
     * Form to create a new Post
     *
     * @return void
     */
    public function newPost()
    {
        $test = 'test';

        return $this->render('admin/newPost.html.twig', []);
    }
    
    /**
     * Add a new post in the database
     *
     * @return void
     */
    public function addPost($title, $heading, $content, $author)
    {
        $datePublication = $dateLastUpdate = date("Y-m-d H:i:s");
        $post = new Post($title, $datePublication, $dateLastUpdate, $heading, $content, $author);
        //var_dump($post);
        $addPostManager = new AddPostManager($post);
        //var_dump($addPostManager);
        $addPostManager->savePost($post);

        return $this->render('admin/addPost.html.twig', [
            'title' => $title,
            'heading' => $heading,
            'content' => $content,
            'author' => $author
        ]);
    }
}