<?php

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
</div>

<script>
    function markAsRead(notificationId) {
        // Simula la marcatura di una notifica come letta
        const notification = document.querySelector(`.list-group-item[data-notification-id="${notificationId}"]`);
        if (notification) {
            notification.classList.remove('active', 'bg-primary-subtle');
            notification.classList.add('bg-secondary-subtle');

            // Call PHP function via AJAX
            fetch('utils/update_notifications.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        notificationIds: [notificationId]
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        alert('Notifica segnata come letta.');
                    } else {
                        alert('Si è verificato un errore durante l\'aggiornamento della notifica.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Si è verificato un errore durante l\'aggiornamento della notifica.');
                });
        } else {
            alert('Notifica non trovata.');
        }
    }

    function markAllAsRead() {
        // Simula la marcatura di tutte le notifiche come lette
        const notifications = document.querySelectorAll('.list-group-item');
        const notificationIds = [];

        notifications.forEach(notification => {
            // Only process unread notifications
            if (notification.classList.contains('bg-primary-subtle')) {
                // Extract notification ID from the p element
                const idText = notification.querySelector('p.mb-1').textContent;
                const notificationId = parseInt(idText.replace('ID Notifica: ', ''));
                notificationIds.push(notificationId);

                // Update UI
                notification.classList.remove('active', 'bg-primary-subtle');
                notification.classList.add('bg-secondary-subtle');
            }
        });

        // Call PHP function for each notification via AJAX
        if (notificationIds.length > 0) {
            fetch('utils/update_notifications.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        notificationIds: notificationIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        alert('Tutte le notifiche sono state segnate come lette.');
                    } else {
                        alert('Si è verificato un errore durante l\'aggiornamento delle notifiche.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Si è verificato un errore durante l\'aggiornamento delle notifiche.');
                });
        } else {
            alert('Tutte le notifiche sono già state lette.');
        }
    }
</script>