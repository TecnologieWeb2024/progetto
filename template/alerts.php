<?php
require_once("core/alertGenerator/alertFactory.php");

// Generic error message
if (isset($_SESSION['error_message'])) {
    $alert = AlertFactory::createAlert('danger', $_SESSION['error_message']);
    $alert->display();
    unset($_SESSION['error_message']);
}

// Generic success message
if (isset($_SESSION['success_message'])) {
    $alert = AlertFactory::createAlert('success', $_SESSION['success_message']);
    $alert->display();
    unset($_SESSION['success_message']);
}

// Registration message
if (isset($_SESSION['registration'])) {
    $type = $_SESSION['registration']['success'] === true ? 'success' : 'danger';
    $alert = AlertFactory::createAlert($type, $_SESSION['registration']['message']);
    $alert->display();
    unset($_SESSION['registration']);
}

// Authentication message
if (isset($_SESSION['auth'])) {
    // Se non abbiamo ancora visualizzato l'alert oppure l'ID corrente è diverso da quello già mostrato...
    if (!isset($_SESSION['auth']['last_alert_displayed']) ||
        $_SESSION['auth']['last_alert_displayed'] !== $_SESSION['auth']['alert_id']) {

        // Determina il tipo dell'alert in base al successo o fallimento
        $type = $_SESSION['auth']['success'] === true ? 'success' : 'danger';
        $alert = AlertFactory::createAlert($type, $_SESSION['auth']['message']);
        $alert->display();

        // Registra l'ID dell'alert appena mostrato
        $_SESSION['auth']['last_alert_displayed'] = $_SESSION['auth']['alert_id'];
    }
}

?>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        setTimeout(() => {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 150);
            });
        }, 3000);
    });
</script>