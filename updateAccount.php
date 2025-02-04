<?php
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];

require_once('core/accountHelper.php');
require_once('bootstrap.php');
$accountHelper = new AccountHelper($dbh);

$changePasswordResult = $accountHelper->changePassword($newPassword, $confirmPassword);

unset($_POST['new_password']);
unset($_POST['confirm_password']);

setcookie('password_change[success]', $changePasswordResult['success'], time() + 3600, '/');
setcookie('password_change[message]', $changePasswordResult['message'], time() + 3600, '/');

if ($changePasswordResult['success'] === true) {
    header('Location: /logout.php');
    exit;
}
header('Location: index.php?page=account');
exit;
?>
