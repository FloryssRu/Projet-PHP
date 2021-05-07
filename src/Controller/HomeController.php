<?php

namespace App\Controller;

use App\Core\BaseController;

class HomeController extends BaseController
{
    public function home()
    {
        $test = 'test';

        return $this->render('home.html.twig', [
            "test" => $test,
            'test2' => 'test2'

        ]);
    }
}