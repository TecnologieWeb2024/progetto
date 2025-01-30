<?php
require_once('core/authenticationHelper.php');
require_once('bootstrap.php');
$authenticator = new AuthenticationHelper($dbh);

$authenticationSuccess = $authenticator->login();

$_SESSION['auth']['success'] = $authenticationSuccess['success'];
$_SESSION['auth']['message'] = $authenticationSuccess['message'];

header('Location: index.php');
?>