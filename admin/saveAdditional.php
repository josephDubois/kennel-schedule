<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$success = 0;
/*loop through each addtional cost and add to database*/
for ($i = 0; $i <= count($_POST['additionalDesc']) - 1; $i++) {
  $bookingId = $_SESSION['bookingId'];
  $desc = mysqli_real_escape_string($link, $_POST['additionalDesc'][$i]);
  $quantity = mysqli_real_escape_string($link, $_POST['additionalQuantity'][$i]);
  $cost = mysqli_real_escape_string($link, $_POST['additionalPrices'][$i]);
  $type = mysqli_real_escape_string($link, $_POST['additionalType'][$i]);

  $query = "INSERT INTO additionalCosts (bookingid, description, quantity, cost, type)
  VALUES ('$bookingId', '$desc', '$quantity', '$cost', '$type')";
  $result=mysqli_query($link, $query);
  if($result) {
    $success++;
  }
}
if ($success == count($_POST['additionalDesc'])) {
  echo "Success";
}else{
  echo "Fail";
}

?>
