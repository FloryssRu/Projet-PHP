<?php

namespace App\Controller\admin;

use App\Core\BaseController;

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
        echo 'le titre est ' . $title;
        /*return $this->render('admin/addPost.html.twig', [
            'title' => $title,
            'heading' => $heading,
            'content' => $content,
            'author' => $author
        ]);*/
    }
}