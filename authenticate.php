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

if (isUserCustomer()) {
    header('Location: index.php');
} else if (isUserSeller()) {
    header('Location: index.php?page=seller');
}
?>