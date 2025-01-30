<?php
require_once('app/authenticationHelper.php');
require_once('bootstrap.php');
$authenticator = new AuthenticationHelper($dbh);

$authenticationSuccess = $authenticator->login();

if($authenticationSuccess['success'] === true) {
    $_SESSION['auth_success'] = true;
    $_SESSION['auth_message'] = $authenticationSuccess['message'];
} else {
    $_SESSION['auth_success'] = false;
    $_SESSION['auth_message'] = $authenticationSuccess['message'];
}

header('Location: index.php');
?>