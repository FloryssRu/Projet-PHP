<?php

namespace App\Controller;

use App\Core\BaseController;

class ErrorController extends BaseController
{
	public function Show($exception) 
	{
		$this->addParam("exception", $exception);
		$this->render("error.html.twig", [
			'message' => $exception->getMessage(),
			'exe' => $exception->getTraceAsString()
		]);
    }
}
	