<?php
require_once('core/registrationHelper.php');
require_once('bootstrap.php');
$registrationHelper = new RegistrationHelper($dbh);

$registrationResult = $registrationHelper->register();

// Verifica se la registrazione ha avuto successo
$_SESSION['registration']['success'] = $registrationResult['success'];
$_SESSION['registration']['message'] = $registrationResult['message'];

header('Location: index.php');
?>