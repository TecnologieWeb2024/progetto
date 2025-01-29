<?php 
session_start();
$_SESSION["testUsername"] = "TestUser";
//Includere functions
require_once("utils/functions.php");

//Includere db
require_once("db/databaseHelper.php");
$servername = "localhost";
$username = "db_user";
$password = "1234";
$dbname = "CaffeBoDB";
$dbport = 3306;
$dbh = new DatabaseHelper($servername, $username, $password, $dbname, $dbport);
?>