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

$query = "SELECT * FROM additionalCosts WHERE bookingid = '$bookingid'";
$result=mysqli_query($link, $query);
$count = 0;
if (mysqli_num_rows($result) >= 0) {
  while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $id[$count] = $row['id'];
    $desc[$count] = $row['description'];
    $quantity[$count] = $row['quantity'];
    $cost[$count] = $row['cost'];
    $type[$count] = $row['type'];
    $count++;
  }
  echo json_encode($id)."*";
  echo json_encode($desc)."*";
  echo json_encode($quantity)."*";
  echo json_encode($cost)."*";
  echo json_encode($type);
}else{
  echo "Fail";
  exit;
}


?>
