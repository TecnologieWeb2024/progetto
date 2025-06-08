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

// Validate form data (basic validation)
$cc_number = isset($_POST['cc_number']) ? preg_replace('/\D/', '', $_POST['cc_number']) : '';
$cc_expiry = isset($_POST['cc_expiry']) ? trim($_POST['cc_expiry']) : '';
$cc_cvc = isset($_POST['cc_cvc']) ? trim($_POST['cc_cvc']) : '';

// In a real app, you'd validate these values more thoroughly
if (strlen($cc_number) < 13 || strlen($cc_cvc) < 3) {
    $_SESSION['error_message'] = 'Dati carta non validi';
    header('Location: index.php?page=payment');
    exit;
}

$transaction_reference = 'CC-' . date('YmdHis');

// Use common payment processor
processPayment($dbh, $controller, 1, $transaction_reference);
