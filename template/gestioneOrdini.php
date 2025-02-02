<?php

require_once('bootstrap.php');
// $user = $dbh->getUserInfo($_SESSION['customer']['user_id']);
?>
<h1 class="text-center">Gestione Ordini</h1>
<div class="container col-md-4 mt-4">
</div>


<div>

    <?php 
    $products = $dbh->getAllProducts();
    $totalProducts = count($products);
    ?>
    <!-- // TODO creare query per gli ordini
    // TODO totale ordini
    // TODO totale ordini da evadere
    // TODO totale ordini evasi
    // TODO totale ordini conclusi -->
    <h5>Nel DB ci sono <?php echo $totalProducts ?> prodotti</h5>
</div>