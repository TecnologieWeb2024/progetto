<?php
require_once("bootstrap.php");
if (isset($_POST['product_id'])) {

    $product_id = $_POST["product_id"];
    
    $dbh->changeAvailability($product_id);
}
header('Location: index.php?page=gestioneProdotti');
?>