<?php

namespace App\Controller;

use App\Core\Controller;

class LoginController extends Controller
{
    public function login()
    {
        require TEMPLATE_DIR . "/login.html.twig";
    }

    public function authenticate($login, $password)
    {
        echo 'Vous vous appellez "' . $login . '" et votre password est bien caché !';
    }
}