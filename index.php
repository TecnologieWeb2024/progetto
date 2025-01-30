<?php
require_once("bootstrap.php");
require_once("utils/router.php");

if (!isset($router)) {
    $router = new Router();
}
$route = $router->getRoute();
// Imposta i parametri del template
$templateParams["main-content"] = $route['file'];
$templateParams["title"] = $route['title'];
$templateParams["aside-content"] = "template/alerts.php";
require("template/base.php");
?>