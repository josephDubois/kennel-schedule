<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$split = mysqli_real_escape_string($link, $_POST['splitValue']);
if ($split == "split" && empty($_SESSION['split'])) {
  $_SESSION['split'] = "split";
  $_SESSION['calendarId'] = time() . rand(10*45, 100*98);
}
/*get all post variables*/
$dogsId = "";
for ($i=0; $i <= count($_POST['kennelDogs']) - 1; $i++){
  $dogsId = $dogsId.mysqli_real_escape_string($link, $_POST['kennelDogs'][$i]).",";
}
$dogsId = substr($dogsId, 0, -1);
$clientId = mysqli_real_escape_string($link, $_POST['clientId']);
$bookingId = $_SESSION['bookingId'];
$kennel = mysqli_real_escape_string($link, $_POST['kennel']);
$startDate = mysqli_real_escape_string($link, $_POST['newCheckIn']);
$endDate = mysqli_real_escape_string($link, $_POST['newCheckOut']);
if ($_SESSION['split'] == "split") {
  $calendarid = $_SESSION['calendarId'];
}else{
  $calendarid = time() . rand(10*45, 100*98);
}
$query = "INSERT INTO overnight (bookingid, calendarid, split, startdate, enddate, clientid, dogsid, kennel)
VALUES ('$bookingId', '$calendarid', '$split', '$startDate', '$endDate', '$clientId', '$dogsId', '$kennel')";
$result=mysqli_query($link, $query);
if($result) {
  echo $bookingId;
}else{
  echo "Fail";
}

?>
