<?php

namespace App\Core;

class HttpRequest
{
    private string $url;
	private string $method;
	private array $param;
	private object $route;
		
	public function __construct($url = null, $method = null)
	{
		if(is_null($url)) { $this->url = $_SERVER['REQUEST_URI']; }
		else { $this->url = $url; }
		if(is_null($method)) { $this->method = $_SERVER['REQUEST_METHOD']; }
        else { $this->method = $method; }
		$this->param = array();
		//$this->params = array();
	}

    public function getUrl(): string
	{
		return $this->url;	
	}

	public function getMethod(): string
	{
		return $this->method;	
	}
		
	public function getParams(): array
	{
		return $this->params;
	}

	public function bindParam()
	{
		switch($this->method)
		{
			case "GET":
			case "DELETE":
				foreach($this->route->getParam() as $param)
				{
					if(isset($_GET[$param]))
					{
						$this->param[] = $_GET[$param];
					}
				}
			case "POST":
			case "PUT":
				foreach($this->route->getParam() as $param)
				{
					if(isset($_POST[$param]))
					{
						$this->param[] = $_POST[$param];
					}
				}
		}
	}

	public function setRoute($route): void
	{
		$this->route = $route;
		//$this->param = $route->getparam(); //modifié
		//var_dump($this->route);
	}

	public function getRoute()
	{
		return $this->route;	
	}

	public function getParam()
	{
		return $this->param;
	}

	public function run()
    {
        $this->bindParam();
		$this->route->run($this);
    }

	public function addParam($value)
    {
        $this->params[] = $value;
    }
}