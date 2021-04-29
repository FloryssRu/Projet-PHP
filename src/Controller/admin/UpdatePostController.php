<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Core\Exceptions\FormNotValidException;
use App\Repository\Manager\UpdatePostManager;

class UpdatePostController extends BaseController
{    
    /**
     * Update a post which just was send with the editpost form
     *
     * @param  mixed $id
     * @param  string $title
     * @param  string $heading
     * @param  string $content
     * @param  string $author
     * @return void
     */
    public function updatePost($id, string $title, string $heading, string $content, string $author)
    {
        $isSubmit = $this->isSubmit('editPost');
        $fields = [$title, $heading, $content, $author];
        $isValid = $this->isValid($fields);

        if($isSubmit == true && $isValid == true) {

            $dateLastUpdate = date("Y-m-d H:i:s");
            $updatePostManager = new UpdatePostManager('post');
            $params = [
                'title' => $title,
                'heading' => $heading,
                'content' => $content,
                'author' => $author,
                'date_last_update' => $dateLastUpdate
            ];
            
            $updatePostManager->update($params, $id);

            return $this->render('admin/confirmation.html.twig', [
                    'message' => "Votre post a bien été modifié."
                ]);

        } else {

            //je veux rediriger vers une erreur mais ça ne fait pas ce que je veux
            throw new FormNotValidException();

        }
    }
}