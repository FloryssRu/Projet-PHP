<?php

namespace App\Controller;

use App\Core\BaseController;

class HomeController extends BaseController
{
    public function home(): void
    {
        $this->render('home.html.twig', []);
    }
}