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
    $_SESSION['error_message'] = 'Utente non autenticato';
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Utente non autenticato'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productId = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? -1;
    $remove = $_POST['remove'] ?? false;

    if($quantity < 0)
    {
        $remove = true;
    }

    if($productId)
    {
        if($remove)
        {
            $result = $dbh->removeProductFromCart($_SESSION['customer']['user_id'], $productId);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => $result['success'],
                    'message' => $result['message'],
                    'product_id' => $productId
                ]);

        } else {
            $result = $dbh->changeCartProductQuantity($_SESSION['customer']['user_id'], $productId, $quantity);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result['success'],
                'message' => $result['message'],
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }
    }
}

?>
