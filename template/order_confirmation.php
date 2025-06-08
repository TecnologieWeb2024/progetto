<?php
require_once('bootstrap.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isUserLoggedIn() || !isUserCustomer()) {
    header('Location: index.php');
    exit;
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order = $dbh->getOrder($order_id);
$order_products = $dbh->getOrderProducts($order_id);

print_r($order_products);
// Verify the order belongs to current user
if (!$order || $order['user_id'] != $_SESSION['customer']['user_id']) {
    $_SESSION['error_message'] = 'Ordine non trovato';
    header('Location: index.php');
    exit;
}

$dbh->insertNotificationNow($_SESSION['customer']['user_id'], 'Il tuo ordine #' . $order_id . ' è stato inserito.');
foreach ($order_products as $product) {
    $dbh->insertNotificationNow($product['seller_id'], 'Inserito ordine per ' . $product['product_name'] . ' × ' . $product['quantity']);
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="mb-4">Grazie per il tuo ordine!</h1>
                    <p class="lead mb-4">Il tuo ordine #<?php echo $order_id; ?> è stato confermato.</p>
                    <p>Riceverai un'email di conferma con tutti i dettagli dell'ordine.</p>

                    <div class="mt-4 p-3 bg-light rounded">
                        <h4>Riepilogo Ordine</h4>
                        <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
                        <p><strong>Totale:</strong> €<?php echo number_format($order['total_price'], 2); ?></p>
                    </div>

                    <div class="mt-4">
                        <a href="index.php" class="btn btn-primary me-3">Torna alla Home</a>
                        <a href="index.php?page=orders" class="btn btn-outline-secondary">I miei ordini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>