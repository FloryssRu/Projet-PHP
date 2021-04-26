<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Repository\Manager\AdminPostsManager;

class AdminPostsController extends BaseController
{
    public function adminPosts()
    {
        $adminPostManager = new AdminPostsManager('Post');
        $getAllPosts = $adminPostManager->getAll();
        var_dump($getAllPosts);

        return $this->render('admin/adminPosts.html.twig', [
            "allPosts" => $getAllPosts,
            'test2' => 'test2'
        ]);
    }
}