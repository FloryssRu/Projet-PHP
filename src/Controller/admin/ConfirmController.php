<?php

namespace App\Controller\admin;

use App\Core\BaseController;

class ConfirmgedController extends BaseController
{    
    /**
     * Confirmation of something (form saved, connexion successful, ...)
     *
     * @return void
     */
    public function confirm()
    {
        return $this->render('admin/confirmation.html.twig', []);
    }

    
}