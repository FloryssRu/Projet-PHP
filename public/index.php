<?php
require '../vendor/autoload.php';


define("CONF_DIR", realpath(dirname(__DIR__)) . "/config");
define("TEMPLATE_DIR", realpath(dirname(__DIR__)) . "/src/templates");

use App\Core\HttpRequest;
use App\Core\Router;


try
{

	$httpRequest = new HttpRequest;
	$router = new Router();
	$httpRequest->setRoute($router->run($httpRequest));
    $httpRequest->run();

}
catch(Exception $e)
{
	$httpRequest = new HttpRequest("/blogphp/Error", "GET", $e);
	$router = new Router();
	$httpRequest->setRoute($router->run($httpRequest));
	$httpRequest->addParam($e);
    $httpRequest->run();
}