<?php

require_once('bootstrap.php');
// $user = $dbh->getUserInfo($_SESSION['customer']['user_id']);
?>
<h1 class="text-center">Gestione Ordini</h1>
<div class="container col-md-4 mt-4">
</div>


<div>

    <?php
    $orders = $dbh->getAllOrders(); // ? Aggiungere range?

    $totalOrders = count($orders);
    ?>
    <!-- 
        // TODO totale ordini
        // TODO totale ordini da evadere
        // TODO totale ordini evasi
        // TODO totale ordini conclusi -->
    <h5>Nel DB ci sono <?php echo $totalOrders ?> ordini</h5>
    <!-- <h5>ORDINI <?php print_r($orders) ?> </h5> -->
</div>

<?php require_once('template/orders.php'); ?>
