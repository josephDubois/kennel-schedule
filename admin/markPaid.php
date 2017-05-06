<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$bookingid = $_SESSION['bookingId'];
$total = $_POST['thisTotal'];
$paid = 1;
$query = "UPDATE costs SET deposit='$total', paid = '$paid' WHERE bookingid = '$bookingid'";
$result=mysqli_query($link, $query);
if($result) {
  echo $bookingid;
}else{
  echo "Fail";
}
?>
