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

if (isUserLoggedIn() === false) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Utente non autenticato'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    // Assicurati di validare e sanitizzare i dati in ingresso
    if ($productId) {
        $result = $dbh->addToCart($_SESSION['customer']['user_id'], $productId, $quantity);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $result['success'],
            'message' => $result['message'],
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Dati non validi'
        ]);
    }
}
