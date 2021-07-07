<?php

namespace App\Core;

use App\Core\Exceptions\MultipleRoutesFoundException;

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
        $routeFound = array_filter($this->listRoute, function($route) use ($httpRequest) {
            if (preg_match('#\/post\/[a-zA-Z\-0-9]+$#', $httpRequest->getUrl())) {
				$url = preg_replace("#\/post\/[a-zA-Z\-0-9]+$#", '/post', $httpRequest->getUrl());
			} else {
                $url = preg_replace("#\?[a-zA-Z]+=[0-9a-zA-Z\-]+(&[a-zA-Z]+=[0-9a-zA-Z\-]+)*#", '', $httpRequest->getUrl());
            }

            $route = preg_match("#^" . $route->path . "$#", $url) && $route->method == $httpRequest->getMethod();
            return $route;
        });
        $numberRoute = count($routeFound);

        if ($numberRoute > 1) {
            throw new MultipleRoutesFoundException();
		} elseif ($numberRoute == 0) {
            $route404 = array_filter($this->listRoute, function($route) use ($httpRequest){

                $url = preg_replace("#^.*$#", '/blogphp/erreur-404', $httpRequest->getUrl());
                $route = preg_match("#^" . $route->path . "$#", $url) && $route->method == $httpRequest->getMethod();
                return $route;
            });
            return new Route(array_shift($route404));
		} else {
			return new Route(array_shift($routeFound));
		}
    }
}