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
$checkIn = $_POST['checkIn1'];
$checkOut = $_POST['checkOut1'];
$totalKennels = $_POST['totalKennels1'];
$doubleBooked;
$booked;
for ($i = 1; $i <= $totalKennels; $i++) {
  /*Check if already booked for that kennel*/
  $query = "SELECT * FROM overnight WHERE kennel = '$i' AND startdate < '$checkOut' AND enddate > '$checkIn' AND bookingid != '$bookingid'";
  $result=mysqli_query($link, $query);
  if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_array($result, MYSQL_ASSOC);
      $booked = $booked.$i."&";
  }else{
      /*Check for a double booking front*/
      $query2 = "SELECT startdate FROM overnight WHERE enddate = '$checkIn' AND kennel = '$i'";
      $result2=mysqli_query($link, $query2);
      if (mysqli_num_rows($result2) > 0) {
        $doubleBookedFront = $doubleBookedFront.$i."&";
       }
     /*Check for a double booking back*/
     $query2 = "SELECT startdate FROM overnight WHERE startdate = '$checkOut' AND kennel = '$i'";
     $result2=mysqli_query($link, $query2);
     if (mysqli_num_rows($result2) > 0) {
       $doubleBookedBack = $doubleBookedBack.$i."&";
      }
    }
}
echo $doubleBookedFront."@".$doubleBookedBack."@".$booked;
?>
