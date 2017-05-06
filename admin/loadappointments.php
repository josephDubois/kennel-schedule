<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}

$day = $_POST['day1'];
$month = $_POST['month1'];
$year = $_POST['year1'];
$limit = $_POST['limit'];
$newDate = $year."-".$month."-".$day;

$query = "SELECT * FROM appointments WHERE INSTR(`dates`, '{$newDate}') > 0 LIMIT $limit";
$result=mysqli_query($link, $query);

if (mysqli_num_rows($result) == 0) {
  echo 'none';
}else{
  $customers = [""];
  while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $id = $row['clientid'];
    $bookingid = $row['bookingid'];
    $dogid = $row['dogid'];
    /*Get type*/
    $query4 = "SELECT * FROM costs WHERE bookingid = '$bookingid'";
    $result4=mysqli_query($link, $query4);
    $row4 = mysqli_fetch_array($result4, MYSQL_ASSOC);
    $type = $row4['bookingType'];
    /*Get Clients Name*/
    $query2 = "SELECT * FROM users WHERE id = '$id'";
    $result2=mysqli_query($link, $query2);
    $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
    $first = $row2['first'];
    $last = $row2['last'];
    $booking = $first." ".$last."&".$type."&";
    /*Get Dog Names*/
    $query3= "SELECT * FROM dogs WHERE dogid = $dogid";
    $result3=mysqli_query($link, $query3);
    $row3 = mysqli_fetch_array($result3, MYSQL_ASSOC);
    $dogName = $row3['name'];
    $booking = $booking.$dogName."*";
     /*Add booking id*/
    $booking = $booking."&".$bookingid;

    array_push($customers, $booking);
  }
  echo json_encode($customers);
}
?>
