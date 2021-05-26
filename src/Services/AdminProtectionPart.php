<?php

namespace App\Services;

use App\Core\BaseController;

class AdminProtectionPart extends BaseController
{

    public function __construct()
    {

    }

    public function redirectNotAdmin()
	{
        $session = new PHPSession;
		if($session->get('admin') == NULL || $session->get('admin') == false)
        {
            $this->redirect('/erreur-403');
        }
		
	}

}
