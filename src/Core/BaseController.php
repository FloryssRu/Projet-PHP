<?php

namespace App\Core;

class BaseController
{
	private $httpRequest;
	private $param;
	private $config;
		
	public function __construct($httpRequest)
	{
		$this->httpRequest = $httpRequest;
		$this->config = $httpRequest;
		$this->param = array();
		$this->addParam("httprequest", $this->httpRequest);
		$this->addParam("config", $this->config);
		$this->bindManager();
	}
		
	protected function render($filename, $array)
	{
		if(file_exists(TEMPLATE_DIR . '//' . $filename))
		{
			extract($this->param);

			$loader = new \Twig\Loader\FilesystemLoader(TEMPLATE_DIR . '//');
			$twig = new \Twig\Environment($loader, ['debug' => true]);
			echo $twig->render($filename, $array);

		} else
		{
			throw new Exceptions\ViewNotFoundException();
		}
	}

	public function redirect(string $path) {
		header("Location:/blogphp" . $path);
		exit();
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