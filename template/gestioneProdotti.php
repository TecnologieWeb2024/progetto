<?php

require_once('bootstrap.php');
// $user = $dbh->getUserInfo($_SESSION['customer']['user_id']);
?>
<h1 class="text-center">Gestione Prodotti</h1>
<div class="container col-md-4 mt-4">
</div>


<div>

    <?php 
    $products = $dbh->getAllProducts();
    $totalProducts = count($products);
    ?>
    <h5>Nel DB ci sono <?php echo $totalProducts ?> prodotti</h5>
</div>