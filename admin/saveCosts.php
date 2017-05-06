<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
if(empty($_SESSION['bookingId'])) {
  $_SESSION['bookingId'] = time() . rand(10*45, 100*98);
  $_SESSION['oneTime'] = "set";
}
$priceRow = mysqli_real_escape_string($link, $_POST['priceRow']);
$subTotal = mysqli_real_escape_string($link, $_POST['subTotal']);
$tax = mysqli_real_escape_string($link, $_POST['tax']);
$total = mysqli_real_escape_string($link, $_POST['finalTotal']);
$deposit = mysqli_real_escape_string($link, $_POST['deposit']);
$clientId = mysqli_real_escape_string($link, $_POST['clientId']);
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
$query = "INSERT INTO costs (paid, bookingid, bookdate, priceRow,subtotal, tax, total, deposit, payType, bookingType, clientid, notes)
VALUES ('$paid', '$bookingId', '$bookdate', '$priceRow','$subTotal', '$tax', '$total', '$deposit', '$paymentType', '$bookingType', '$clientId', '$paymentNote')";
$result=mysqli_query($link, $query);

if($result) {
  echo $bookingId;
}else{
  echo "Fail";
}
?>
