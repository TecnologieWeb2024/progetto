<?php 
session_start();
$_SESSION["testUsername"] = "TestUser";
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