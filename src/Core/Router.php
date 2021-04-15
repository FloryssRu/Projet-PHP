<?php

namespace App\Core;

class Router
{
    private array $listRoute;

    public function __construct()
    {
        $stringRoute = file_get_contents('../config/routes.json');
		$this->listRoute = json_decode($stringRoute);
    }

    public function run(HttpRequest $httpRequest)
    {

        $routeFound = array_filter($this->listRoute, function($route) use ($httpRequest){
            
            //var_dump($route->path);
            //var_dump($httpRequest->getUrl());
            //var_dump($route->method);
            //var_dump($httpRequest->getMethod());
            $route = preg_match("#^" . $route->path . "$#", $httpRequest->getUrl()) && $route->method == $httpRequest->getMethod();

            //var_dump($route);
            return $route;

        });
        //var_dump($routeFound);
        $numberRoute = count($routeFound);

        if($numberRoute > 1)
		{
            throw new Exceptions\MultipleRoutesFoundException;
		}
		else if($numberRoute == 0)
		{
			throw new Exceptions\NoRouteFoundException($httpRequest);
		}
		else
		{
			return new Route(array_shift($routeFound));
		}
    }
}