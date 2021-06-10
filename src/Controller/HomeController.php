<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Services\HandlerSaveContactMessage;

class HomeController extends BaseController
{    
    /**
     * Display the home page
     */
    public function home()
    {
        return $this->render('home.html.twig');
    }
    
    /**
     * Save the message in database send by the contact form and send an email to each admin
     */
    public function saveMessage()
    {
        $saveContactMessage = new HandlerSaveContactMessage;
        return $saveContactMessage->saveMessageHandler();
    }
}