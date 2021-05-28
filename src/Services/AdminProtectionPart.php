<?php

namespace App\Services;

use App\Core\BaseController;

class AdminProtectionPart extends BaseController
{

    /**
     * Is empty because the mother class has a construct function and we don't want to use it for this class.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function redirectNotAdmin()
	{
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            $this->redirect('/erreur-403');
        }
		
	}

}
