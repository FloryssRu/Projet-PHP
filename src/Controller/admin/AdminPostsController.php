<?php

namespace App\Controller\admin;

use App\Core\BaseController;

class AdminPostsController extends BaseController
{
    public function adminPosts()
    {
        $test = 'test';

        return $this->render('adminPosts.html.twig', [
            "test" => $test,
            'test2' => 'test2'

        ]);
    }
}