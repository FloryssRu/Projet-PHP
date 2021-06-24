<?php

namespace App\Controller;

use App\Core\BaseController;

class ErrorController extends BaseController
{
	public function Show($exception = NULL)
	{
        if($exception == NULL)
        {
            return $this->redirect('/erreur-404');
        }
		$this->addParam("exception", $exception);
		return $this->render("error.html.twig", [
			'message' => $exception->getMessage()
		]);
    }

	public function error403()
    {
        return $this->render('403.html.twig');
    }

    public function error404()
    {
        return $this->render('404.html.twig');
    }
}
	