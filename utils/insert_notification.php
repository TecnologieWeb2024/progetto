<?php
require_once '../bootstrap.php'; // Include la connessione al DB e il DBHelper

header('Content-Type: application/json');

// Verifica che la richiesta sia POST e JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER['CONTENT_TYPE']) &&
    strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {

    // Leggi il corpo della richiesta
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Controlla che siano presenti i parametri richiesti
    if (isset($data['user_id'], $data['message'])) {
        $user_id = filter_var($data['user_id'], FILTER_VALIDATE_INT);
        $message = trim($data['message']);

        if ($user_id && $message !== '') {
            try {
                $success = $dbh->insertNotificationNow($user_id, $message);
                echo json_encode(['success' => $success]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Errore durante l\'inserimento: ' . $e->getMessage()
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Dati non validi']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Parametri mancanti']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Richiesta non valida']);
}
