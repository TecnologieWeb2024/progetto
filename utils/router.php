<?php 
class Router 
{
    private $pageMap = [
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
        'orders' => [
            'file' => 'template/orders.php',
            'title' => 'CoffeeBo - Ordini',
        ],
        'account' => [
            'file' => 'template/account.php',
            'title' => 'CoffeeBo - Account',
        ],
    ];

    public function getRoute() 
    {
        $page = isset($_GET['page']) && array_key_exists($_GET['page'], $this->pageMap) ? $_GET['page'] : 'home';
        return $this->pageMap[$page];
    }
}

?>