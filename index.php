<?php
require_once("bootstrap.php");
require_once("utils/router.php");

$router = new Router();
$route = $router->getRoute();
// Imposta i parametri del template
$templateParams["main-content"] = $route['file'];
$templateParams["title"] = $route['title'];

require("template/base.php");
?>