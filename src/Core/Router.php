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

            $url = preg_replace("#\?[a-zA-Z]+=[0-9a-zA-Z\-]+(&[a-zA-Z]+=[0-9a-zA-Z\-]+)?#", '', $httpRequest->getUrl());
            $route = preg_match("#^" . $route->path . "$#", $url) && $route->method == $httpRequest->getMethod();

            return $route;

        });
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