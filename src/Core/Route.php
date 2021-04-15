<?php

namespace App\Core;

use App\Core\Exceptions\ControllerNotFoundException;
use App\Core\Exceptions\ActionNotFoundException;

class Route
{
	private string $path;
	private string $controller;
	private string $action;
	private string $method;
	private array $param;
	private array $manager;
	
	public function __construct(object $route)
	{
		$this->path = $route->path;
		$this->controller = $route->controller;
		$this->action = $route->action;
		$this->method = $route->method;
		$this->param = $route->param;
		//$this->params = $route->param;
		$this->manager = $route->manager;
	}
		
	public function getPath(): string
	{
		return $this->path;
	}
		
	public function getController(): string
	{
		return $this->controller;
	}
		
	public function getAction(): string
	{
		return $this->action;
	}
		
	public function getMethod(): string
	{
		return $this->method;
	}
		
	public function getParam(): array
	{
		return $this->param;
	}

	public function getManager()
	{
		return $this->manager;
	}

	public function run($httpRequest)
	{
		//var_dump($httpRequest);
		$controller = null;
		$controllerName = 'App\Controller\\' . $this->controller . 'Controller';
		//var_dump(class_exists($controllerName));
		
        if(class_exists($controllerName))
        {
            $controller = new $controllerName($httpRequest);

            if(method_exists($controller, $this->action))
            {
				//var_dump($httpRequest);
				$controller->{$this->action}(...$httpRequest->getparam());
            }
            else
            {
                throw new ActionNotFoundException;
            }
        }
        else
        {
            throw new ControllerNotFoundException;
        }
	}
}