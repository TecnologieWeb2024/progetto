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
    <form method="get" action="index.php" class="mb-4 mt-4">
        <input type="hidden" name="page" value="seller">

        <div class="d-flex align-items-center justify-content-center">
            <label for="yearSelector" class="form-label me-2 mb-0">
                <h3 class="mb-0">Statistiche</h3>
            </label>
            <select id="yearSelector" name="year" class="form-select w-auto" onchange="this.form.submit()">
                <?php
                $currentYear = date("Y");
                $startYear = $currentYear - 10;
                for ($year = $currentYear; $year >= $startYear; $year--) {
                    $selected = (isset($_GET['year']) && $_GET['year'] == $year) ? 'selected' : '';
                    echo "<option value=\"$year\" $selected>$year</option>";
                }
                ?>
            </select>
        </div>
    </form>
    <div class="row">
        <div class="col-md-6 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Totale vendite</h5>
                    <p class="card-text">
                        <?php
                        $totalSales = $dbh->getTotalSales($_SESSION['seller']['user_id'], $_GET['year'] ?? date("Y"));
                        echo number_format($totalSales, 2, ',', '.');
                        ?> €
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Totale ordini</h5>
                    <p class="card-text">
                        <?php
                        $totalOrders = $dbh->getTotalOrders($_SESSION['seller']['user_id'], $_GET['year'] ?? date("Y"));
                        echo $totalOrders ?? 0;
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6 mt-4">
            <div class="card">
                <div class="card-body" style="min-height: 12em;">
                    <h5 class="card-title">Prodotti più venduti</h5>
                    <ol class="mb-0 ps-0" style="list-style-position:inside;">
                        <?php
                        $topProducts = $dbh->getBestSellingProducts($_SESSION['seller']['user_id'], $_GET['year'] ?? date("Y"));
                        foreach (array_slice($topProducts, 0, 5) as $product) {
                            echo isset($product['product_name'], $product['quantity'])
                                ? '<li>' . $product['product_name'] . " &times; " . $product['quantity'] . '</li>'
                                : "";
                        }
                        ?>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-4">
            <div class="card">
                <div class="card-body" style="min-height: 12em;">
                    <h5 class="card-title">Migliori acquirenti</h5>
                    <ol class="mb-0 ps-0" style="list-style-position:inside;">
                        <?php
                        $topCustomers = $dbh->getBestCustomers($_SESSION['seller']['user_id'], $_GET['year'] ?? date("Y"));
                        foreach (array_slice($topCustomers, 0, 5) as $customer) {
                            echo isset($customer['email'], $customer['total_spent'])
                                ? '<li>' . $customer['email'] . ": " . number_format($customer['total_spent'], 2) . " €</li>"
                                : "";
                        }
                        ?>
                    </ol>

                </div>
            </div>
        </div>
    </div>
</div>