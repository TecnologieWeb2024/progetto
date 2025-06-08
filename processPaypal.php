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

// Validate form data
$paypal_email = isset($_POST['paypal_email']) ? trim($_POST['paypal_email']) : '';
$paypal_password = isset($_POST['paypal_password']) ? trim($_POST['paypal_password']) : '';

if (empty($paypal_email) || empty($paypal_password)) {
    $_SESSION['error_message'] = 'Inserisci email e password PayPal';
    header('Location: index.php?page=payment');
    exit;
}

$transaction_reference = 'PP-' . date('YmdHis');

// Use common payment processor
processPayment($dbh, $controller, 2, $transaction_reference);
