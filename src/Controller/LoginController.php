<?php

namespace App\Controller;

use App\Core\BaseController;

class LoginController extends BaseController
{
    public function login()
    {
        $test = 'test';

        return $this->render('login.html.twig', [
            "test" => $test,
            'test2' => 'test2'
        ]);
    }

    public function authenticate($login, $password)
    {
        echo 'Vous vous appellez "' . $login . '" et votre password est bien caché !';
    }
}