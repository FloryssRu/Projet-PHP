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