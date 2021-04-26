<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Post;
use App\Repository\Manager\AddPostManager;


class AddPostController extends BaseController
{   
    
    /**
     * Add a new post to the database
     *
     * @return void
     */
    public function addPost(string $title, string $heading, string $content, string $author)
    {
        //$isSubmit = $this->isSubmit('newPost');
        $datePublication = $dateLastUpdate = date("Y-m-d H:i:s");
        $post = new Post($title, $datePublication, $dateLastUpdate, $heading, $content, $author);
        $addPostManager = new AddPostManager($post);
        $addPostManager->savePost($post);

        return $this->render('admin/confirmation.html.twig', [
                'message' => "Votre nouveau post a bien été enregistré."
            ]);

    }
}