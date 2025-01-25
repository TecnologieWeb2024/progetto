<?php
require_once("bootstrap.php");

// Mapping di pagine valide
$pageMap = [
    'home' => [
        'file' => 'template/landing-page.php',
        'title' => 'CoffeeBo - Home',
    ],
    'products' => [
        'file' => 'template/products.php',
        'title' => 'CoffeeBo - Prodotti',
    ],
    'contacts' => [
        'file' => 'template/contacts.php',
        'title' => 'CoffeeBo - Contatti',
    ],
];

// Verifica che la pagina richiesta sia valida, altrimenti usa il default
$page = isset($_GET['page']) && array_key_exists($_GET['page'], $pageMap) ? $_GET['page'] : 'home';

// Imposta i parametri del template
$templateParams["main-content"] = $pageMap[$page]['file'];
$templateParams["title"] = $pageMap[$page]['title'];

require("template/base.php");
