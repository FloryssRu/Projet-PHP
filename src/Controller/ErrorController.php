<?php

namespace App\Controller;

use App\Core\BaseController;

class ErrorController extends BaseController
{
	public function Show($exception): void
	{
		$this->addParam("exception", $exception);
		return $this->render("error.html.twig", [
			'message' => $exception->getMessage()
		]);
    }
}
	