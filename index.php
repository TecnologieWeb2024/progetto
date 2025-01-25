<?php
require_once("bootstrap.php");

$templateParams["titolo"] = "CoffeeShop - Home";

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'products':
        $templateParams["main-content"] = "template/products.php";
        break;
    case 'contacts':
        $templateParams["main-content"] = "template/contacts.php";
        break;
    case 'home':
    default:
        $templateParams["main-content"] = "template/landing-page.php";
        break;
}
require("template/base.php");
