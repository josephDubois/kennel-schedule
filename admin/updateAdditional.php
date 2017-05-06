<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$bookingId = $_SESSION['bookingId'];
$sendData;
/*loop through each addtional cost and update to database*/
for ($i = 0; $i <= count($_POST['masterIdList']) - 1; $i++) {
  $id = mysqli_real_escape_string($link, $_POST['masterIdList'][$i]);
  $desc = mysqli_real_escape_string($link, $_POST['additionalDesc'][$i]);
  $quantity = mysqli_real_escape_string($link, $_POST['additionalQuantity'][$i]);
  $cost = mysqli_real_escape_string($link, $_POST['additionalPrices'][$i]);
  $type = mysqli_real_escape_string($link, $_POST['additionalType'][$i]);
  $query = "UPDATE additionalCosts SET description = '$desc', quantity = '$quantity', cost = '$cost', type = '$type' WHERE id = '$id'";
  $result=mysqli_query($link, $query);
  if(mysqli_affected_rows($link) >= 0) {
    $sendData = "success";
    for ($i = count($_POST['masterIdList']); $i <= count($_POST['additionalDesc']) - 1; $i++) {
      $desc = mysqli_real_escape_string($link, $_POST['additionalDesc'][$i]);
      $quantity = mysqli_real_escape_string($link, $_POST['additionalQuantity'][$i]);
      $cost = mysqli_real_escape_string($link, $_POST['additionalPrices'][$i]);
      $type = mysqli_real_escape_string($link, $_POST['additionalType'][$i]);
      $query = "INSERT INTO additionalCosts (bookingid, description, quantity, cost, type)
      VALUES ('$bookingId', '$desc', '$quantity', '$cost', '$type')";
      $result=mysqli_query($link, $query);
      if($result) {
        $sendData = "success";
      }else{
        $sendData = "fail";
      }
    }
  }else{
    $sendData = "fail";
  }
}
echo $sendData;


?>
