<?php
require_once('core/authenticationHelper.php');
require_once('bootstrap.php');
$authenticator = new AuthenticationHelper($dbh);

$authenticationSuccess = $authenticator->login();

$_SESSION['auth'] = [
    'success' => $authenticationSuccess['success'],
    'message' => $authenticationSuccess['message'],
    'alert_id' => uniqid()
];

header('Location: /index.php');
?>