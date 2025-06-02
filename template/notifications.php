<?php
require_once('bootstrap.php');

// session_start();

$user_id = $_SESSION['customer']['user_id'] ?? $_SESSION['seller']['user_id'];

if (isset($_SESSION['customer'])) {
    $real_notifications = $dbh->getAllUserNotifications($_SESSION['customer']['user_id']);
} elseif (isset($_SESSION['seller'])) {
    $real_notifications = $dbh->getAllUserNotifications($_SESSION['seller']['user_id']);
}

$formatted_notifications = [];

foreach ($real_notifications as $row) {
    $formatted_notifications[] = [
        'notification_id' => (int)$row['notification_id'],
        'user_id' => $user_id,
        'message' => $row['message'],
        'is_read' => (bool)$row['is_read'],  // conversione esplicita
        'created_at' => $row['created_at']
    ];
}


?>
<script src="js/timer.js"></script>
<script src="js/notifications.js"></script>
<h1 class="text-center">Le tue notifiche</h1>
<div class="container mt-4">
    <div class="list-group">
        <?php foreach ($formatted_notifications as $notification): ?>
            <a href="#" class="list-group-item list-group-item-action text-black <?php echo $notification['is_read'] ? 'bg-secondary-subtle' : 'active bg-primary-subtle'; ?>" onclick="markAsRead(<?php echo $notification['notification_id']; ?>)" data-notification-id="<?php echo $notification['notification_id']; ?>">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?php echo htmlspecialchars($notification['message']); ?></h5>
                    <small><?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?></small>
                </div>
                <!-- <p class="mb-1">ID Notifica: <?php echo $notification['notification_id']; ?></p> -->
            </a>
        <?php endforeach; ?>
    </div>
    <div class="mt-4 text-center align-items-center justify-content-center">
        <button class="btn btn-primary" onclick="markAllAsRead()">Segna tutte come lette</button>
        <button class="btn btn-warning" onclick="markAllAsNotRead()">Segna tutte come non lette</button>
    </div>
    <div class="mt-4 text-center align-items-center justify-content-center">
        <button class="btn btn-success" onclick="avviaTimer(5)">Avvia TIMER</button>
        <button class="btn btn-danger" onclick="fermaTimer()">Ferma TIMER</button>
    </div>
</div>
