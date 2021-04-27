<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Repository\Manager\AdminPostsManager;

class UpdatePostController extends BaseController
{
    public function updatePost()
    {
        $adminPostManager = new AdminPostsManager('Post');
        $getThisPost = $adminPostManager->getById($_GET['idPost']);
        $title = $getThisPost['title'];
        $content = $getThisPost['content'];
        $heading = $getThisPost['heading'];
        $author = $getThisPost['author'];

        return $this->render('admin/updatePost.html.twig', [
            'title' => $title,
            'content' => $content,
            'heading' => $heading,
            'author' => $author
        ]);
    }
}