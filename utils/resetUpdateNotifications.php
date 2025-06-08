<?php
//? Copiaincolla per resettare le notifiche in fase di development


require_once '../bootstrap.php';

// Check if the request is a POST request with JSON content
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER['CONTENT_TYPE']) &&
    strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
) {

    // Get the raw POST data and decode it
    $json = file_get_contents('php://input');
    // Make an array out of it
    $data = json_decode($json, true);

    // Check if notification IDs are provided
    if (isset($data['notificationIds']) && is_array($data['notificationIds'])) {
        $success = true;

        // Update each notification
        foreach ($data['notificationIds'] as $notification_id) {
            // Validate notification ID
            $notification_id = filter_var($notification_id, FILTER_VALIDATE_INT);
            if ($notification_id) {
                try {
                    $dbh->markNotificationAsNotRead($notification_id);
                } catch (Exception $e) {
                    $success = false;
                    error_log("Error updating notification ID $notification_id: " . $e->getMessage());
                }
            }
        }

        // Return response as JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    } else {
        // No valid notification IDs provided
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'No valid notification IDs provided']);
    }
} else {
    // Invalid request method or content type
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
