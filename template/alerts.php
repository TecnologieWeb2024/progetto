<?php
require_once("core/alertGenerator/alertFactory.php");

if (isset($_SESSION['registration_errors'])) {
    $errors = $_SESSION['registration_errors'];
    foreach ($errors as $error) {
        $alert = AlertFactory::createAlert('danger', $error);
        $alert->display();
    }
    unset($_SESSION['registration_errors']);
}

if (isset($_SESSION['error_message'])) {
    $alert = AlertFactory::createAlert('danger', $_SESSION['error_message']);
    $alert->display();
    unset($_SESSION['error_message']);
}

if (isset($_SESSION['registration_success'])) {
    $alert = AlertFactory::createAlert('success', $_SESSION['registration_success']);
    $alert->display();
    unset($_SESSION['registration_success']);
}

if (isset($_SESSION['auth_message'])) {
    echo "<script>console.log('" . $_SESSION['auth_message'] . "');</script>";
    $type = $_SESSION['auth_success'] === true ? 'success' : 'danger';
    $alert = AlertFactory::createAlert($type, $_SESSION['auth_message']);
    $alert->display();
    unset($_SESSION['auth_message']);
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