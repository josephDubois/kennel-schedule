<?php

session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
//get dates and id//
$startDate = '';
$endDate = '';
$id = $_POST['id'];
$kennel = $_POST['kennel'];
for($i = 0; $i <= count($_POST['startDatesArray']) - 1; $i++) {
  if($i == count($_POST['startDatesArray']) - 1) {
    $startDate = $startDate.$_POST['startDatesArray'][$i];
    $endDate = $endDate.$_POST['endDatesArray'][$i];
  }else{
    $startDate = $startDate.$_POST['startDatesArray'][$i]."/";
    $endDate = $endDate.$_POST['endDatesArray'][$i]."/";
  }
}

$query = "UPDATE bookings".date("Y")." SET startdate='$startDate', enddate='$endDate', kennel='$kennel' WHERE id='$id'";
$result=mysqli_query($link, $query);
if (mysqli_affected_rows($link) > 0) {
    echo "Success";
}else{
    echo "Fail";
    echo $startDate." ".$endDate;
}

 ?>
