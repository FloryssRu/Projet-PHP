<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Post;
use App\Repository\Manager\AddPostManager;
use App\Core\Exceptions\FormNotValidException;


class AddPostController extends BaseController
{   
    
    /**
     * Add a new post to the database
     *
     * @return void
     */
    public function addPost(string $title, string $heading, string $content, string $author)
    {
        $isSubmit = $this->isSubmit('newPost');
        $fields = [$title, $heading, $content, $author];
        $isValid = $this->isValid($fields);

        if($isSubmit == true && $isValid == true) {

            $datePublication = $dateLastUpdate = date("Y-m-d H:i:s");
            $addPostManager = new AddPostManager('post');

            $params = [
                'title' => $title,
                'heading' => $heading,
                'content' => $content,
                'author' => $author,
                'date_publication' =>$datePublication,
                'date_last_update' => $dateLastUpdate
            ];  

            $addPostManager->insert($params);

            return $this->render('admin/confirmation.html.twig', [
                    'message' => "Votre nouveau post a bien été enregistré."
                ]);

        } else {

            //je veux rediriger vers une erreur mais ça ne fait pas ce que je veux
            throw new FormNotValidException();

        }
    }
}