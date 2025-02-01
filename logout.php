<?php
require_once('core/authenticationHelper.php');
$authenticator = new AuthenticationHelper($dbh);

$authenticator->logout();
header('Location: /index.php');
?>