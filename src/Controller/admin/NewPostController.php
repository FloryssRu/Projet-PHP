<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Post;
use App\Repository\Manager\AddPostManager;

class NewPostController extends BaseController
{    
    /**
     * Form to create a new Post
     *
     * @return void
     */
    public function newPost()
    {
        return $this->render('admin/newPost.html.twig', []);
    }
    
}