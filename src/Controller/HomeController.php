<?php

namespace App\Controller;

use App\Core\BaseController;

class HomeController extends BaseController
{
    public function home()
    {
        /*$loader = new \Twig\Loader\FilesystemLoader(TEMPLATE_DIR);
        $twig = new \Twig\Environment($loader, [
            //'cache' => '/path/to/compilation_cache',
        ]);

        $template = $twig->load('accueil.html.twig');*/

        //require TEMPLATE_DIR . "/home.html.twig";
        $test = 'test';

        return $this->render('home.html.twig', [
            "test" => $test,
            'test2' => 'test2'

        ]);
    }
}