<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
include("functions.php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}

$query = "SELECT * FROM kennels";
$result=mysqli_query($link, $query);
$numberOfKennels = mysqli_num_rows($result);
$day = $_POST['day1'];
$month = $_POST['month1'];
$year = $_POST['year1'];
goToDate($numberOfKennels, $month, $year, $day);


?>
