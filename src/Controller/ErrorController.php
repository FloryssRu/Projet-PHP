<?php

namespace App\Controller;

class ErrorController
{
	public function Show($exception) 
	{
		$this->addParam("exception", $exception);
		$this->render("error");
    }
}
	