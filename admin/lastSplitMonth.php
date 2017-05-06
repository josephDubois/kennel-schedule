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
$month = $_POST['month'];
$year = $_POST['year'];
loadNextSplitMonth($numberOfKennels, $month, $year);


?>
