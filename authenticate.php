<?php
require_once('app/authenticationHelper.php');
require_once('bootstrap.php');
$authenticator = new AuthenticationHelper($dbh);

$authenticator->login();

if ($_SESSION['auth_success'] === true) {
    header('Location: index.php');
} else {
    echo 'Email o password errati.';
}
?>