<?php

require_once('bootstrap.php');
?>
<h1 class="text-center">Gestione Prodotti</h1>


<div class="container text-center">

    <?php
    $products = $dbh->getAllProducts();
    $totalProducts = count($products);
    ?>
</div>

<?php require_once('template/products.php'); ?>