<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//Includere functions
require_once("utils/functions.php");

//Includere db
require_once("db/databaseHelper.php");
$servername = "localhost";
$db_username = "db_user";
$db_password = "1234";
$dbname = "CaffeBoDB";
$dbport = 3306;
$dbh = new DatabaseHelper($servername, $db_username, $db_password, $dbname, $dbport);
?>