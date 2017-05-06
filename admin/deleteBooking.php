<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$type = $_POST['typeOfBooking'];
$bookingid = $_SESSION['bookingId'];
$success = 0;
$query = "DELETE FROM additionalCosts WHERE bookingid='$bookingid'";
$result=mysqli_query($link, $query);
if (mysqli_affected_rows($link) >= 0) {
  $success++;
}
$query = "DELETE FROM costs WHERE bookingid='$bookingid'";
$result=mysqli_query($link, $query);
if (mysqli_affected_rows($link) >= 0) {
  $success++;
}
if ($type == "Over Night") {
  $query = "DELETE FROM overnight WHERE bookingid='$bookingid'";
  $result=mysqli_query($link, $query);
  if (mysqli_affected_rows($link) >= 0) {
    $success++;
  }
}else{
  $query = "DELETE FROM appointments WHERE bookingid='$bookingid'";
  $result=mysqli_query($link, $query);
  if (mysqli_affected_rows($link) >= 0) {
    $success++;
  }
}

if ($success == 3) {
  echo "Success";
}else{
  echo "Fail";
}

?>
