<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}

$_SESSION['bookingId'] = $_POST['bookingid'];
$bookingid = $_POST['bookingid'];
$bookingType;
$payType;
$deposit;
$bookDate;

$query = "SELECT * FROM costs WHERE bookingid = '$bookingid'";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result, MYSQL_ASSOC);
if (mysqli_num_rows($result) <= 0) {
    echo "Fail";
    exit;
}else{
  $bookingType = $row['bookingType'];
  $payType = $row['paytype'];
  $deposit = $row['deposit'];
  $bookDate = $row['bookdate'];
  $notes = $row['notes'];
}


echo $bookingType."*".$payType."*".$deposit."*".$bookDate."*".$notes;
?>
