<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}

$bookingtype = $_POST['bookingtype'];
$bookingid = $_SESSION['bookingId'];

if ($bookingtype == "Over Night") {
  //overnight booking details//
  $query = "SELECT * FROM overnight WHERE bookingid = '$bookingid' ORDER BY kennel";
  $result=mysqli_query($link, $query);
  $count = 0;
  $checkIn;
  $checkOut;
  $clientId;
  $editDogsIds = [""];
  $editKennel = [""];
  $split;
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
      if ($row['split'] == "split") {
        $split = $row['split'];
        $clientId = $row['clientid'];
        $editKennel[$count] = $row['kennel'];
        $editDogsIds = $row['dogsid'];
        $checkIn[$count] = $row['startdate'];
        $checkOut[$count] = $row['enddate'];
        $calendarid = $row['calendarid'];
        $count++;
      }else if($split == "split") {
        $clientId = $row['clientid'];
        $editKennel[$count] = $row['kennel'];
        $checkIn[$count] = $row['startdate'];
        $checkOut[$count] = $row['enddate'];
        $count++;
      }else{
        $checkIn = $row['startdate'];
        $checkOut = $row['enddate'];
        $clientId = $row['clientid'];
        $editDogsIds[$count] = str_replace(",",".", $row['dogsid']);
        $editKennel[$count] = $row['kennel'];
        $count++;
      }
    }
    //CHECK IF ITS A SPLIT BOOKING//
    if ($split == "split") {
      $_SESSION['splitCalendarDate'] = $checkIn[0];
      echo json_encode($checkIn)."*";
      echo json_encode($checkOut)."*";
      echo $clientId."*";
      echo $editDogsIds."*";
      echo json_encode($editKennel)."*";
      echo $split."*";
      echo $calendarid;
    }else{
      echo $checkIn."*";
      echo $checkOut."*";
      echo $clientId."*";
      echo json_encode($editDogsIds)."*";
      echo json_encode($editKennel)."*";
      echo $split;
    }

  }else{
    echo "Fail";
    exit;
  }

}else{
  //appointments booking details//
  $query = "SELECT * FROM appointments WHERE bookingid = '$bookingid' ORDER BY dogid";
  $result=mysqli_query($link, $query);
  $count = 0;
  $dates = [""];
  $clientId;
  $editDogsIds = [""];
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
      $dates[$count] = $row['dates'];
      $clientId = $row['clientid'];
      $editDogsIds[$count] = str_replace(",",".", $row['dogid']);
      $count++;
    }
    echo $clientId.".";
    echo json_encode($editDogsIds).".";
    echo json_encode($dates);
  }else{
    echo "Fail";
    exit;
  }
}

?>
