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

// print_r($_SESSION);
if (array_key_exists('customer', $_SESSION)) {
    header('Location: index.php');
} else if (array_key_exists('seller', $_SESSION)) {
    header('Location: index.php?page=seller');
}
?>