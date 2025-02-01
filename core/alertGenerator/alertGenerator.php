<?php
require_once("core/alertGenerator/alertFactory.php");

class AlertGenerator
{
    /**
     * Seleziona il tipo di alert da visualizzare e crea l'oggetto Alert corrispondente, quindi lo visualizza.
     * @param bool $success true per alert di successo, false per alert di errore.
     * @param string $message Il messaggio da visualizzare nell'alert.
     */
    public static function processAlert($success, $message)
    {
        $type = $success === true || $success === '1' ? 'success' : 'danger';
        $alert = AlertFactory::createAlert($type, $message);
        $alert->display();
    }

    /**
     * Visualizza tutti gli alert presenti nella sessione e li rimuove dopo la visualizzazione.
     */
    public static function displayAllAlerts()
    {
        // Example for simple session alerts using the common method
        if (isset($_SESSION['error_message'])) {
            self::processAlert(false, $_SESSION['error_message']);
            unset($_SESSION['error_message']);
        }
        if (isset($_SESSION['success_message'])) {
            self::processAlert(true, $_SESSION['success_message']);
            unset($_SESSION['success_message']);
        }

        // Special case: registration alert stored as array with a success flag and message
        if (isset($_SESSION['registration'])) {
            self::processAlert($_SESSION['registration']['success'], $_SESSION['registration']['message']);
            unset($_SESSION['registration']);
        }

        // Special case: authentication alert with duplicate prevention
        if (isset($_SESSION['auth'])) {
            if (
                !isset($_SESSION['auth']['last_alert_displayed']) ||
                $_SESSION['auth']['last_alert_displayed'] !== $_SESSION['auth']['alert_id']
            ) {
                self::processAlert($_SESSION['auth']['success'], $_SESSION['auth']['message']);
                $_SESSION['auth']['last_alert_displayed'] = $_SESSION['auth']['alert_id'];
            }
        }

        // Process cookie alerts (example: password_change)
        if (isset($_COOKIE['password_change']['message'])) {
            self::processAlert($_COOKIE['password_change']['success'], $_COOKIE['password_change']['message']);
            setcookie('password_change[success]', '', time() - 3600, '/');
            setcookie('password_change[message]', '', time() - 3600, '/');
        }
    }
}
?>