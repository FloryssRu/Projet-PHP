<?php

namespace App\Controller;

use App\Core\BaseController;

class UserController extends BaseController
{
	public function Login()
	{
		$this->render("login");
	}
		
	public function Authenticate($login,$password)
	{
		var_dump($login,$password);
	}
}