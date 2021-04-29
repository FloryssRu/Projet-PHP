<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Repository\Manager\AdminPostsManager;

class EditPostController extends BaseController
{
    public function editPost()
    {
        $adminPostManager = new AdminPostsManager('Post');
        $getThisPost = $adminPostManager->getById($_GET['idPost']);

        return $this->render('admin/editPost.html.twig', [
            'id' => $getThisPost['id'],
            'title' => $getThisPost['title'],
            'content' => $getThisPost['content'],
            'heading' => $getThisPost['heading'],
            'author' => $getThisPost['author']
        ]);
    }
}