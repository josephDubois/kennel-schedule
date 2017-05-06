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
$newDate = $year."/".$month."/".$day;

$query = "SELECT * FROM overnight WHERE enddate = '$newDate' LIMIT $limit";
$result=mysqli_query($link, $query);

if (mysqli_num_rows($result) == 0) {
  echo 'none';
}else{
  $customers = [""];
  while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $id = $row['clientid'];
    $bookingid = $row['bookingid'];
    $kennel = $row['kennel'];
    $dogsid = $row['dogsid'];
    $dogsid = explode(",", $dogsid);
    /*Get Clients Name*/
    $query2 = "SELECT * FROM users WHERE id = '$id'";
    $result2=mysqli_query($link, $query2);
    $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
    $first = $row2['first'];
    $last = $row2['last'];
    $booking = $first." ".$last."&".$kennel."&";
    /*Get Dogs Names*/
    for($i=0; $i <= count($dogsid) - 1;$i++){
      $query3= "SELECT * FROM dogs WHERE dogid = $dogsid[$i]";
      $result3=mysqli_query($link, $query3);
      $row3 = mysqli_fetch_array($result3, MYSQL_ASSOC);
      $dogName = $row3['name']."^".$dogsid[$i];
      $booking = $booking.$dogName."*";
    }
    /*Add booking id*/
    $booking = $booking."&".$bookingid;

    array_push($customers, $booking);
  }
  echo json_encode($customers);
}
?>
