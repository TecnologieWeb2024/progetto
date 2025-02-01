<?php
require_once("core/alertGenerator/alertFactory.php");
require_once("core/alertGenerator/alertGenerator.php");

AlertGenerator::displayAllAlerts();
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