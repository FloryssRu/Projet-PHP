<?php

namespace App\Core;

use App\Core\Response\Redirection;
use App\Core\Response\Response;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class BaseController
{
	private $httpRequest;
	private $param;
	private $config;
	protected $twig;

	public function __construct($httpRequest)
	{
		$this->httpRequest = $httpRequest;
		$this->config = $httpRequest;
		$this->param = array();
		$this->addParam("httprequest", $this->httpRequest);
		$this->addParam("config", $this->config);
		$this->bindManager();
		$loader = new FilesystemLoader(TEMPLATE_DIR . '//');
		$this->twig = new Environment($loader, ['debug' => true]);
	}
		
	protected function render($filename, $array = [])
	{
		if(file_exists(TEMPLATE_DIR . '//' . $filename))
		{
			extract($this->param);

			$this->twig->addGlobal('session', $_SESSION);
			$view = $this->twig->load($filename);
			$content = $view->render($array);
			$response = new Response($content);
			return $response->send();

			/*echo $twig->render($filename, $array);*/

		} else
		{
			throw new Exceptions\ViewNotFoundException();
		}
	}

	public function redirect(string $path)
	{
		$redirection = new Redirection($path);
		return $redirection->redirect($path);
	}
	
	/**
	 * Verify that a form has been submitted
	 *
	 * @param  string $buttonSubmit
	 * @return boolean
	 */
	public function isSubmit(string $buttonSubmit)
	{
		if(isset($_POST[$buttonSubmit])) {
			return true;
		}
	}
	
	/**
	 * Verify that all fileds are not empty and are valid
	 *
	 * @param  array $fields Array containing all the fields to check
	 * @return boolean
	 */
	public function isValid(array $fields)
	{
		$isValid = true;
		foreach($fields as $value) {
			if($value == NULL || !isset($value) || $value == '') {
				$isValid = false;
			}
		}
		
		return $isValid;
	}
		
	public function bindManager()
	{
		foreach($this->httpRequest->getRoute()->getManager() as $manager)
			{
				$this->$manager = new $manager($this->config->database);
			}
	}

	public function addParam($name, $value)
	{
		$this->param[$name] = $value;
	}
}