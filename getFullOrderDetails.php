<?php
require_once('bootstrap.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$orderId = (int)$_POST['order_id'];
header('Content-Type: application/json');

// Recupera i dati base
$response = $dbh->getFullOrderDetails($orderId);
if (!$response['success']) {
    echo json_encode($response);
    exit;
}

// Aggiungi l'array items alla risposta
$data = $response['data'];
$data['items'] = $dbh->getOrderProducts($orderId);

// Assicura che total_price sia float
$data['total_price'] = (float)$data['total_price'];

echo json_encode([
    'success' => true,
    'data'    => $data,
    'message' => $response['message']
]);
?>