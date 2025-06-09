<?php

require_once('bootstrap.php');
$user = $dbh->getUserInfo($_SESSION['seller']['user_id']);
$sellerProducts = $dbh->getProductsBySeller($_SESSION['seller']['user_id']);
foreach ($sellerProducts as $product) {
    if ($product['stock'] <= 0) {
        $dbh->insertNotificationNow($_SESSION['seller']['user_id'], 'Il prodotto "' . $product['product_name'] . '" è esaurito.');
    }
}
// $user = $dbh->getUserInfo($_SESSION['customer']['user_id']);
?>
<h2 class="text-center h1">Benvenuto, <?php echo $user['first_name'] ?> </h2>
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
                <span class="h3 mb-0">Statistiche</span>
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
                    <h3 class="card-title">Totale vendite</h3>
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
                    <h3 class="card-title">Totale ordini</h3>
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
                    <h3 class="card-title">Prodotti più venduti</h3>
                    <ol class="mb-0 ps-0" style="list-style-position:inside;">
                        <?php
                        $topProducts = $dbh->getBestSellingProducts($_SESSION['seller']['user_id'], $_GET['year'] ?? date("Y"));
                        foreach (array_slice($topProducts, 0, 5) as $product) {
                            echo isset($product['product_name'], $product['total_sold'])
                                ? '<li>' . $product['product_name'] . " &times; " . $product['total_sold'] . '</li>'
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
                    <h3 class="card-title">Migliori acquirenti</h3>
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
    <div class="row mb-4">
        <div class="col-md-12 h-50">
            <div class="card px-0 mx-0">
                <div class="card-body px-0 mx-0" style="min-height: 12em;">
                    <h3 class="card-title">Ricavi, Ricavo medio per ordine e Numero di ordini mensili</h3>
                    <?php
                    $result = $dbh->getAverageMonthlyRevenue(
                        $_SESSION['seller']['user_id'],
                        $_GET['year'] ?? date("Y")
                    );
                    // Abbreviazioni mesi
                    $shortMonths = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
                    $labels = array_map(function ($item) use ($shortMonths) {
                        return $shortMonths[date('n', strtotime($item['month'])) - 1];
                    }, $result);
                    $totalRevenue = array_map(fn($item) => (float)$item['total_revenue'], $result);
                    $avgPerOrder  = array_map(fn($item) => (float)$item['avg_revenue_per_order'], $result);
                    $ordersCount  = array_map(fn($item) => (int)$item['orders_count'], $result);
                    ?>
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="min-height: 12em;">
                    <h3 class="card-title">Giorni di stock rimanenti per prodotto</h3>
                    <table class="table table-sm table-striped caption-top table-responsive">
                        <caption>Prodotti in magazzino</caption>
                        <thead class="">
                            <tr>
                                <th>Prodotto</th>
                                <th>Stock</th>
                                <th>Vendite Giornaliere</th>
                                <th>Giorni rimanenti</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stockProducts = $dbh->getStockDays($_SESSION['seller']['user_id'], $_GET['year'] ?? date("Y"));
                            foreach ($stockProducts as $product) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($product['product_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($product['stock']) . '</td>';
                                echo '<td>' . htmlspecialchars($product['avg_daily_sold'] ?? 0) . '</td>';
                                echo '<td>' . htmlspecialchars($product['days_of_stock'] ?? "-") . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');

    const labels = <?php echo json_encode($labels); ?>;
    const totalRevenue = <?php echo json_encode($totalRevenue); ?>;
    const avgPerOrder = <?php echo json_encode($avgPerOrder); ?>;
    const ordersCount = <?php echo json_encode($ordersCount); ?>;
    const data = {
        labels,
        datasets: [{
                type: 'line',
                label: 'Ricavato (€)',
                data: totalRevenue,
                backgroundColor: 'rgba(75,192,192,0.5)',
                borderColor: 'rgba(75,192,192,1)',
                borderWidth: 1,
                yAxisID: 'y' // unico asse Y
            },
            {
                type: 'line',
                label: 'Ricavo medio per ordine (€)',
                data: avgPerOrder,
                backgroundColor: 'rgba(255,159,64,0.2)',
                borderColor: 'rgba(255,159,64,1)',
                borderWidth: 2,
                fill: false,
                yAxisID: 'y' // lo stesso asse Y del bar
            },
            {
                type: 'line',
                label: 'Numero di ordini',
                data: ordersCount,
                backgroundColor: 'rgba(153,102,255,0.2)',
                borderColor: 'rgba(153,102,255,1)',
                borderWidth: 1,
                yAxisID: 'y1' // lo stesso asse Y del bar
            }
        ]
    };

    const config = {
        data,
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Mese'
                    }
                },
                y: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Valore (€)'
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Numero di ordini'
                    },
                    grid: {
                        drawOnChartArea: false // only want the grid lines for one axis to show up
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const v = ctx.parsed.y;
                            return `${ctx.dataset.label}: ${v}`;
                        }
                    }
                }
            }
        }
    };

    new Chart(ctx, config);
</script>