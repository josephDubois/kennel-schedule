<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
  $query = "SELECT * FROM costsList";
  $result=mysqli_query($link, $query);
  $count = 0;
  while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $description[$count] = $row['description'];
    $basePrice[$count] = $row['basePrice'];
    $type[$count] = $row['type'];
    $count++;
  }

if (mysqli_num_rows($result) > 0) {
  echo json_encode($description)."&";
  echo json_encode($basePrice)."&";
  echo json_encode($type);
}else{
  echo "Fail";
}

?>
