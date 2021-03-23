<?php

use IgorPC\TrackPackage\Router;

require_once ('src/autoload.php');
require_once ("src/cors.php");

$router = new Router($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

return $router->render();
