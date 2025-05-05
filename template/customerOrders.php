<?php
require_once('bootstrap.php');
if (!isset($_SESSION['customer']['user_id'])) {
    header('Location: /login.php');
    exit;
}
$orders = $dbh->getAllUserOrders($_SESSION['customer']['user_id']);
?>
<h1 class="text-center">I tuoi ordini</h1>

<?php require_once('template/orders.php'); ?>