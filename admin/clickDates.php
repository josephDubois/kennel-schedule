<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}

$day = $_POST['day1'];
$month = $_POST['month1'];
$year = $_POST['year1'];
$direction = $_POST['direction1'];

if ($direction == 'back') {
  $date = $month."-".$day."-".$year;
  $prev_date = date('m-d-Y', strtotime($date .' -1 day'));
}else{
  $date = $month."-".$day."-".$year;
  $prev_date = date('m-d-Y', strtotime($date .' +1 day'));
}

//set the display of the date//
echo $prev_date;

?>
