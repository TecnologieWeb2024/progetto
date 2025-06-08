<?php
require_once('bootstrap.php');
require_once('core/paymentController.php');
require_once('utils/paymentProcessor.php');

if (!isUserLoggedIn() || !isUserCustomer()) {
    header('Location: index.php');
    exit;
}

// Initialize controller
$controller = new PaymentController($dbh);

$transaction_reference = 'BTC-' . date('YmdHis');

// Use common payment processor
processPayment($dbh, $controller, 5, $transaction_reference);
