<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}

$checkIn = $_POST['startdate'];
$checkOut = $_POST['enddate'];
$split = $_POST['splitBooking'];
$kennels = [""];
for ($i = 0; $i <= count($_POST['kennels']) - 1; $i++) {
  $kennels[$i] = $_POST['kennels'][$i];
}
for ($i = 0; $i <= count($kennels) - 1; $i++) {
  if ($split == "true") {
    $startDate = $checkIn[$i];
    $endDate = $checkOut[$i];
  }else{
    $startDate = $checkIn;
    $endDate = $checkOut;
  }
  $bookingid = $_SESSION['bookingId'];
  //Check if already booked for that kennel//
  $query = "SELECT * FROM overnight WHERE kennel = '$kennels[$i]' AND startdate < '$endDate' AND enddate > '$startDate'";
  $result=mysqli_query($link, $query);
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
    $bookingID = $row['bookingid'];
    if ($bookingID != $bookingid) {
      echo "Fail".$bookingid." ".$bookingID." ".$kennels[$i]." ".$startDate;
      print_r(array_values($kennels));
      print_r(array_values($checkIn));
      exit;
    }
  }
}
echo "success";
?>
