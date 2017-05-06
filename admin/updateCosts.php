<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$priceRow = mysqli_real_escape_string($link, $_POST['priceRow']);
$subTotal = mysqli_real_escape_string($link, $_POST['subTotal']);
$tax = mysqli_real_escape_string($link, $_POST['tax']);
$total = mysqli_real_escape_string($link, $_POST['finalTotal']);
$deposit = mysqli_real_escape_string($link, $_POST['deposit']);
$paymentNote = mysqli_real_escape_string($link, $_POST['paymentNote']);
$bookingType = $_POST['typeOfBooking'];
$bookdate = date("Y-m-d");
$bookingId = $_SESSION['bookingId'];
$paymentType = mysqli_real_escape_string($link, $_POST['paymentType']);
$paid = $total - $deposit;
if($paid == 0) {
  $paid = 1;
}else{
  $paid = 0;
}
$query = "UPDATE costs SET paid='$paid', priceRow='$priceRow', subtotal='$subTotal', tax='$tax', total='$total', deposit='$deposit', payType='$paymentType', bookingType='$bookingType', notes='$paymentNote' WHERE bookingid = $bookingId";
$result=mysqli_query($link, $query);

if($result) {
  echo $bookingId;
}else{
  echo "Fail";
}
?>
