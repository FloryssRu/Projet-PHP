<?php

namespace App\Controller;

use App\Core\BaseController;

class HomeController extends BaseController
{
    public function home()
    {
        return $this->render('home.html.twig', []);
    }
}