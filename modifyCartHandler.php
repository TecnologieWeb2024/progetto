<?php
set_exception_handler(function ($exception) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ]
    ]);
    exit();
});

require_once('bootstrap.php');

header('Content-Type: application/json');

if (isUserLoggedIn() === false) {
    $_SESSION['error_message'] = 'Utente non autenticato';
    echo json_encode([
        'success' => false,
        'message' => 'Utente non autenticato'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Metodo HTTP non supportato'
    ]);
    exit();
}

$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
$remove = isset($_POST['remove']) ? filter_var($_POST['remove'], FILTER_VALIDATE_BOOLEAN) : false;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : null;

if (!$productId) {
    echo json_encode([
        'success' => false,
        'message' => 'ID prodotto non fornito o non valido.'
    ]);
    exit();
}

if ($quantity === null || $quantity < 0) {
    $remove = true;
}

if ($remove) {
    $result = $dbh->removeProductFromCart($_SESSION['customer']['user_id'], $productId);
    echo json_encode([
        'success' => $result['success'],
        'message' => $result['message'],
        'product_id' => $productId
    ]);
} else {
    $result = $dbh->changeCartProductQuantity($_SESSION['customer']['user_id'], $productId, $quantity);
    echo json_encode([
        'success' => $result['success'],
        'message' => $result['message'],
        'product_id' => $productId,
        'quantity' => $quantity
    ]);
}
?>