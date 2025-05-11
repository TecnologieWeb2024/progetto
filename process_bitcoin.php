<?php
require_once('bootstrap.php');
require_once('core/PaymentController.php');
require_once('utils/payment_processor.php');

if (!isUserLoggedIn() || !isUserCustomer()) {
    header('Location: index.php');
    exit;
}

// Initialize controller
$controller = new PaymentController($dbh);

$transaction_reference = 'BTC-' . date('YmdHis');

// Use common payment processor
processPayment($dbh, $controller, 5, $transaction_reference);
?>
