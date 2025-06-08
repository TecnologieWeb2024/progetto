<?php
require_once '../bootstrap.php';

header('Content-Type: application/json');

// Log locale di debug (solo per sviluppo)
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
error_reporting(E_ALL);

try {
    if (
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_SERVER['CONTENT_TYPE']) &&
        strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
    ) {
        $json = file_get_contents('php://input');

        if ($json === false) {
            throw new Exception("Errore nella lettura del corpo della richiesta.");
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Errore nel parsing JSON: " . json_last_error_msg());
        }

        if (!isset($data['order_id'], $data['new_status'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dati mancanti']);
            exit;
        }

        $orderId = intval($data['order_id']);
        $newStatus = intval($data['new_status']);

        $result = $dbh->updateOrderStatus($orderId, $newStatus);
        if (!is_array($result) || !isset($result['success'])) {
            throw new Exception("Risultato inatteso da updateOrderStatus()");
        }

        if ($result['success'] === true) {
            $dbh->insertNotificationNow($dbh->getOrder($orderId)['user_id'], 'Il tuo ordine #' . $orderId . ' è ' . $dbh->getOrderStatus($orderId)['descrizione']);
            echo json_encode(['success' => true, 'message' => 'Stato ordine aggiornato con successo']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Richiesta non valida o Content-Type errato']);
    }
} catch (Throwable $e) {
    // Log dell’eccezione
    error_log("Errore critico in updateOrderStatus.php: " . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Errore interno: ' . $e->getMessage() // Rimuovere il messaggio in prod
    ]);
}
