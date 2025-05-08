<?php
require_once('bootstrap.php');
$orderId = (int)($_POST['order_id'] ?? 0);
header('Content-Type: application/json');

// 1) Recupera header + pagamento + spedizione
$baseResp = $dbh->getFullOrderDetails($orderId);
if (!$baseResp['success']) {
    echo json_encode($baseResp);
    exit;
}
$data = $baseResp['data'];

$items = isUserSeller() ? $dbh->getSellerOrderDetails($orderId, $_SESSION['seller']['user_id']) : $dbh->getOrderProducts($orderId);

$data['items'] = $items;

// 3) Assicura il tipo corretto su total_price
$data['total_price'] = (float)$data['total_price'];

// 4) Rispondi
echo json_encode([
    'success' => true,
    'data'    => $data,
    'message' => $baseResp['message']
]);
