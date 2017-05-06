<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
/*get all post variables*/
$dates = "";
for ($i=0; $i <= count($_POST['selectedDates']) - 1; $i++){
  $dates = $dates.mysqli_real_escape_string($link, $_POST['selectedDates'][$i])."*";
}
$clientId = mysqli_real_escape_string($link, $_POST['clientId']);
$dogsId = mysqli_real_escape_string($link, $_POST['id']);
$bookingId = $_SESSION['bookingId'];
$query = "INSERT INTO appointments (bookingid, dates, clientid, dogid)
VALUES ('$bookingId','$dates', '$clientId', '$dogsId')";
$result=mysqli_query($link, $query);
if($result) {
  echo $bookingId;
}else{
  echo "Fail";
}
?>
