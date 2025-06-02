<?php
require_once('bootstrap.php');


if (isset($_SESSION['customer'])) {
    $real_notifications = $dbh->getAllUserNotifications($_SESSION['customer']['user_id']);
} elseif (isset($_SESSION['seller'])) {
    $real_notifications = $dbh->getAllUserNotifications($_SESSION['seller']['user_id']);
}

if (empty($real_notifications)) {
    echo "Nessuna notifica disponibile.";
} else {
    foreach ($real_notifications as $notifica) {
        echo $notifica['message'];
    }
}

//$notifications = $dbh->getNotifications($_SESSION['customer']['user_id'] ?? $_SESSION['seller']['user_id']);
$fake_notifications = [
    [
        'notification_id' => 1,
        'user_id' => $_SESSION['customer']['user_id'] ?? $_SESSION['seller']['user_id'],
        'message' => 'Benvenuto su CoffeeBo! Scopri i nostri prodotti e le offerte speciali.',
        'is_read' => false,
        'created_at' => '2023-10-01 12:00:00'
    ],
    [
        'notification_id' => 2,
        'user_id' => $_SESSION['customer']['user_id'] ?? $_SESSION['seller']['user_id'],
        'message' => 'Il tuo ordine #12345 è stato spedito e arriverà presto.',
        'is_read' => false,
        'created_at' => '2023-10-02 14:30:00'
    ],
    [
        'notification_id' => 3,
        'user_id' => $_SESSION['customer']['user_id'] ?? $_SESSION['seller']['user_id'],
        'message' => 'Hai ricevuto un nuovo messaggio dal supporto clienti.',
        'is_read' => true,
        'created_at' => '2023-10-03 09:15:00'
    ],
    [
        'notification_id' => 4,
        'user_id' => $_SESSION['customer']['user_id'] ?? $_SESSION['seller']['user_id'],
        'message' => 'Il tuo profilo è stato aggiornato con successo.',
        'is_read' => true,
        'created_at' => '2023-10-04 11:45:00'
    ],
    [
        'notification_id' => 5,
        'user_id' => $_SESSION['customer']['user_id'] ?? $_SESSION['seller']['user_id'],
        'message' => 'Non perdere le nostre offerte speciali! Visita il nostro sito per maggiori dettagli.',
        'is_read' => false,
        'created_at' => '2023-10-05 16:20:00'
    ]
];
?>
<script src="js/timer.js"></script>
<script src="js/notifications.js"></script>
<h1 class="text-center">Le tue notifiche</h1>
<div class="container mt-4">
    <div class="list-group">
        <?php foreach ($fake_notifications as $notification): ?>
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
    </div>
    <div class="mt-4 text-center align-items-center justify-content-center">
        <button class="btn btn-success" onclick="avviaTimer(5)">Avvia TIMER</button>
        <button class="btn btn-danger" onclick="fermaTimer()">Ferma TIMER</button>
    </div>
</div>
