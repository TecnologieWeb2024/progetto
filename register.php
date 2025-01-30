<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('core/registrationHelper.php');
require_once('bootstrap.php');
$registrationHelper = new RegistrationHelper($dbh);

try {
    $registrationResult = $registrationHelper->register();
} catch (Exception $e) {
    // In caso di errore, salva l'errore nella sessione e fai il redirect
    $_SESSION['error_message'] = 'Si è verificato un errore: ' . $e->getMessage();
}

// Verifica se la registrazione ha avuto successo
if ($registrationResult['success'] === true) {
    $_SESSION['registration_success'] = $registrationResult['message'];
} else {
    if (isset($_SESSION['registration_errors'])) {
        unset($_SESSION['registration_errors']);
    }
    // Salva gli errori nella sessione e fai il redirect alla pagina di registrazione
    $_SESSION['registration_errors'] = $registrationResult['errors'];
}

header('Location: index.php');