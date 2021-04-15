<?php
require '../vendor/autoload.php';




session_start();

define("CONF_DIR", realpath(dirname(__DIR__)) . "/config");
define("TEMPLATE_DIR", realpath(dirname(__DIR__)) . "/src/templates");

use App\Core\HttpRequest;
use App\Core\Router;


try
{

	$httpRequest = new HttpRequest;
	$router = new Router();
	$httpRequest->setRoute($router->run($httpRequest));
	//var_dump($router->run($httpRequest)); //bon
    $httpRequest->run();

}
catch(Exception $e)
{
	echo 'Problème attrapé';
	$httpRequest = new HttpRequest("/blogphp/Error", "GET");
	$router = new Router();
	$httpRequest->setRoute($router->run($httpRequest));
	$httpRequest->addParam($e);
    $httpRequest->run();
}