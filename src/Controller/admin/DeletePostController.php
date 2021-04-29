<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Repository\Manager\DeletePostManager;

class DeletePostController extends BaseController
{    
    /**
     * Delete the post of the line
     *
     * @param  mixed $id
     * @param  string $title
     * @param  string $heading
     * @param  string $content
     * @param  string $author
     * @return void
     */
    public function deletePost($id)
    {
        $deletePostManager = new DeletePostManager('post');
        echo $deletePostManager->delete($id);

        return $this->render('admin/confirmation.html.twig', [
            'message' => "Votre post a bien été supprimé."
        ]);
    }
}