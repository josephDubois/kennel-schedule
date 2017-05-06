<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$_SESSION['updateTime'] = "update";
$bookingId = $_SESSION['bookingId'];
$totalKennels = [""];
//FIND OUT TOTAL NUMBER OF KENNELS ALREADY BOOKED//
$query = "SELECT * FROM overnight WHERE bookingid='$bookingId'";
$result=mysqli_query($link, $query);
if ($result) {
  $counter = 0;
  while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $totalKennels[$counter] = $row['kennel'];
    $counter++;
  }
}else{
  echo "Fail Number of Existing Bookings";
  exit;
}


$split = mysqli_real_escape_string($link, $_POST['splitValue']);
$kennels = [""];
for ($i=0; $i <= count($_POST['updateKennels']) - 1; $i++){
  $kennels[$i] = mysqli_real_escape_string($link, $_POST['updateKennels'][$i]);
}
$dogsId = [""];
for ($i=0; $i <= count($_POST['updateDogs']) - 1; $i++){
  $dogsId[$i] = mysqli_real_escape_string($link, $_POST['updateDogs'][$i]);
}
$clientId = mysqli_real_escape_string($link, $_POST['clientId']);
//IF ITS A SLIT BOOKING//
if ($split == "split") {

  $startDate = mysqli_real_escape_string($link, $_POST['newCheckIn']);
  $endDate = mysqli_real_escape_string($link, $_POST['newCheckOut']);
  $startDate = [""];
  for ($i=0; $i <= count($_POST['newCheckIn']) - 1; $i++){
    $startDate[$i] = mysqli_real_escape_string($link, $_POST['newCheckIn'][$i]);
  }
  $endDate = [""];
  for ($i=0; $i <= count($_POST['newCheckOut']) - 1; $i++){
    $endDate[$i] = mysqli_real_escape_string($link, $_POST['newCheckOut'][$i]);
  }

  if (count($totalKennels) == count($kennels)) {
    $success = 0;
    $calendarid = time() . rand(10*45, 100*98);
    for ($i = 0; $i <= count($kennels) - 1; $i++) {
      if ($i == count($kennels) - 1) {
        $split = "";
      }
      $query = "UPDATE overnight SET split = '$split', clientid = '$clientId', startdate = '$startDate[$i]', enddate = '$endDate[$i]', dogsid = '$dogsId[$i]', calendarid = '$calendarid', kennel = '$kennels[$i]' WHERE bookingid = '$bookingId' AND kennel = '$totalKennels[$i]'";
      $result=mysqli_query($link, $query);
      if (mysqli_affected_rows($link) >= 0) {
        $success++;
      }
    }
    if ($success == (count($kennels))) {
      echo $bookingId;
    }else{
      echo "Fail";
      exit;
    }
  //IF NUMBER OF KENNELS ALREADY BOOKED IS MORE THAN KENNELS TO BE BOOKED//
  }else if(count($totalKennels) >= count($kennels)){
    //FIRST DELETE EXTRA KENNELS ALREADY BOOKED//
    $difference = count($totalKennels) - count($kennels);
    for ($i = 0; $i <= $difference - 1; $i++) {
      $query = "DELETE FROM overnight WHERE bookingid='$bookingId' AND kennel = '$totalKennels[$i]'";
      $result=mysqli_query($link, $query);
      if (mysqli_affected_rows($link) <= 0) {
        echo "Fail";
        exit;
      }
    }
    array_splice($totalKennels,0,$difference);
    //NOW UPDATE THE KENNELS//
    $success = 0;
    $calendarid = time() . rand(10*45, 100*98);
    for ($i = 0; $i <= count($kennels) - 1; $i++) {
      if ($i == count($kennels) - 1) {
        $split = "";
      }
      $query = "UPDATE overnight SET split = '$split', clientid = '$clientId', startdate = '$startDate[$i]', enddate = '$endDate[$i]', dogsid = '$dogsId[$i]', calendarid = '$calendarid', kennel = '$kennels[$i]' WHERE bookingid = '$bookingId' AND kennel = '$totalKennels[$i]'";
      $result=mysqli_query($link, $query);
      if (mysqli_affected_rows($link) >= 0) {
        $success++;
      }
    }
    if ($success == count($kennels)) {
      echo $bookingId;
    }else{
      echo "Fail Saving Less than number of kennels";
      exit;
    }
  //IF NUMBER OF KENNELS ALREADY BOOKED IS LESS THAN KENNELS TO BE BOOKED//
  }else if(count($totalKennels) <= count($kennels)) {
    //FIRST UPDATE THE KENNELS THAT DO EXIST//
    $success = 0;
    $i = 0;
    $calendarid = time() . rand(10*45, 100*98);
    for ($i; $i <= count($totalKennels) - 1; $i++) {
      $query = "UPDATE overnight SET split = '$split', clientid = '$clientId', startdate = '$startDate[$i]', enddate = '$endDate[$i]', dogsid = '$dogsId[$i]', calendarid = '$calendarid', kennel = '$kennels[$i]' WHERE bookingid = '$bookingId' AND kennel = '$totalKennels[$i]'";
      $result=mysqli_query($link, $query);
      if (mysqli_affected_rows($link) >= 0) {
        $success++;
      }
    }
    //NOW SAVE NEW KENNELS TO BE BOOKED//
    for ($i; $i <= count($kennels) - 1; $i++) {
      if ($i == count($kennels) - 1) {
        $split = "";
      }
      $query = "INSERT INTO overnight (bookingid, calendarid, split, startdate, enddate, clientid, dogsid, kennel)
      VALUES ('$bookingId', '$calendarid', '$split', '$startDate[$i]', '$endDate[$i]', '$clientId', '$dogsId[$i]', '$kennels[$i]')";
      $result=mysqli_query($link, $query);
      if (mysqli_affected_rows($link) >= 0) {
        $success++;
      }
    }
    if ($success == count($kennels)) {
      echo $bookingId;
    }else{
      echo "Fail Saving More than number of kennels";
    }
  }

}else{
  $startDate = mysqli_real_escape_string($link, $_POST['newCheckIn']);
  $endDate = mysqli_real_escape_string($link, $_POST['newCheckOut']);
  //IF NUMBER OF KENNELS ALREADY BOOKED IS EQUAL TO KENNELS TO BE BOOKED//
  if (count($totalKennels) == count($kennels)) {
    $success = 0;
    for ($i = 0; $i <= count($kennels) - 1; $i++) {
      $calendarid = time() . rand(10*45, 100*98);
      $query = "UPDATE overnight SET split = '$split', clientid = '$clientId', startdate = '$startDate', enddate = '$endDate', dogsid = '$dogsId[$i]', calendarid = '$calendarid', kennel = '$kennels[$i]' WHERE bookingid = '$bookingId' AND kennel = '$totalKennels[$i]'";
      $result=mysqli_query($link, $query);
      if (mysqli_affected_rows($link) >= 0) {
        $success++;
      }
    }
    if ($success == (count($kennels))) {
      echo $bookingId;
    }else{
      echo "Fail";
      exit;
    }
  //IF NUMBER OF KENNELS ALREADY BOOKED IS MORE THAN KENNELS TO BE BOOKED//
  }else if(count($totalKennels) >= count($kennels)){
    //FIRST DELETE EXTRA KENNELS ALREADY BOOKED//
    $difference = count($totalKennels) - count($kennels);
    for ($i = 0; $i <= $difference - 1; $i++) {
      $query = "DELETE FROM overnight WHERE bookingid='$bookingId' AND kennel = '$totalKennels[$i]'";
      $result=mysqli_query($link, $query);
      if (mysqli_affected_rows($link) <= 0) {
        echo "Fail";
        exit;
      }
    }
    array_splice($totalKennels,0,$difference);
    //NOW UPDATE THE KENNELS//
    $success = 0;
    for ($i = 0; $i <= count($kennels) - 1; $i++) {
      $calendarid = time() . rand(10*45, 100*98);
      $query = "UPDATE overnight SET split = '$split', clientid = '$clientId', startdate = '$startDate', enddate = '$endDate', dogsid = '$dogsId[$i]', calendarid = '$calendarid', kennel = '$kennels[$i]' WHERE bookingid = '$bookingId' AND kennel = '$totalKennels[$i]'";
      $result=mysqli_query($link, $query);
      if (mysqli_affected_rows($link) >= 0) {
        $success++;
      }
    }
    if ($success == count($kennels)) {
      echo $bookingId;
    }else{
      echo "Fail Saving Less than number of kennels";
      exit;
    }
  //IF NUMBER OF KENNELS ALREADY BOOKED IS LESS THAN KENNELS TO BE BOOKED//
  }else if(count($totalKennels) <= count($kennels)) {
    //FIRST UPDATE THE KENNELS THAT DO EXIST//
    $success = 0;
    $i = 0;
    for ($i; $i <= count($totalKennels) - 1; $i++) {
      $calendarid = time() . rand(10*45, 100*98);
      $query = "UPDATE overnight SET split = '$split', clientid = '$clientId', startdate = '$startDate', enddate = '$endDate', dogsid = '$dogsId[$i]', calendarid = '$calendarid', kennel = '$kennels[$i]' WHERE bookingid = '$bookingId' AND kennel = '$totalKennels[$i]'";
      $result=mysqli_query($link, $query);
      if (mysqli_affected_rows($link) >= 0) {
        $success++;
      }
    }
    //NOW SAVE NEW KENNELS TO BE BOOKED//
    for ($i; $i <= count($kennels) - 1; $i++) {
      $calendarid = time() . rand(10*45, 100*98);
      $query = "INSERT INTO overnight (bookingid, calendarid, split, startdate, enddate, clientid, dogsid, kennel)
      VALUES ('$bookingId', '$calendarid', '$split', '$startDate', '$endDate', '$clientId', '$dogsId[$i]', '$kennels[$i]')";
      $result=mysqli_query($link, $query);
      if (mysqli_affected_rows($link) >= 0) {
        $success++;
      }
    }
    if ($success == count($kennels)) {
      echo $bookingId;
    }else{
      echo "Fail Saving More than number of kennels";
    }
  }
}
?>
