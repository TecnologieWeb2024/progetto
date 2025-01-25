<?php
require_once("bootstrap.php");

$templateParams["titolo"] = "CoffeeShop - Home";

// Whitelist di pagine per controlli di sicurezza
$allowedPages = ['products', 'contacts', 'home'];
$page = isset($_GET['page']) && in_array($_GET['page'], $allowedPages) ? $_GET['page'] : 'home';

switch ($page) {
    case 'products':
        $templateParams["main-content"] = "template/products.php";
        $templateParams["titolo"] = "CoffeeShop - Prodotti";
        break;
    case 'contacts':
        $templateParams["main-content"] = "template/contacts.php";
        $templateParams["titolo"] = "CoffeeShop - Contatti";

        break;
    case 'home':
    default:
        $templateParams["main-content"] = "template/landing-page.php";
        $templateParams["titolo"] = "CoffeeShop - Home";
        break;
}
require("template/base.php");
