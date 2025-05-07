<?php

require_once('bootstrap.php');
$user = $dbh->getUserInfo($_SESSION['seller']['user_id']);
// $user = $dbh->getUserInfo($_SESSION['customer']['user_id']);
?>
<h1 class="text-center">Benvenuto, <?php echo $user['first_name'] ?> </h1>
<div class="container col-md-4 mt-4">
    <div class="d-flex justify-content-center gap-2">
        <button type="button" class="btn btn-primary w-50 py-3" onclick="window.location='index.php?page=gestioneProdotti'">Gestione prodotti</button>
        <button type="button" class="btn btn-primary w-50 py-3" onclick="window.location='index.php?page=gestioneOrdini'">Gestione ordini</button>
    </div>
</div>
<div class="container text-center">
    <h3 class="mt-4">Statistiche</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Totale vendite</h5>
                    <p class="card-text">
                        <?php
                        //$totalSales = $dbh->getTotalSales($_SESSION['seller']['user_id']);
                        //echo number_format($totalSales, 2, ',', '.');
                        ?> €
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Totale ordini</h5>
                    <p class="card-text">
                        <?php
                        //$totalOrders = $dbh->getTotalOrders($_SESSION['seller']['user_id']);
                        echo $totalOrders;
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Prodotti più venduti</h5>
                    <p class="card-text">
                        <?php
                        //$topProducts = $dbh->getTopProducts($_SESSION['seller']['user_id']);
                        foreach ($topProducts as $product) {
                            echo $product['product_name'] . "<br>";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Migliori acquirenti</h5>
                    <p class="card-text">
                        <?php
                        //$topCustomers = $dbh->getTopCustomers($_SESSION['seller']['user_id']);
                        foreach ($topCustomers as $customer) {
                            echo $customer['email'] . "<br>";
                            echo $customer['total_spent'] . " €<br>";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>