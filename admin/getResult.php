<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}

$search = mysqli_real_escape_string($link, $_POST['search1']);

$query = "SELECT * FROM users WHERE MATCH(first, last) AGAINST('$search' IN BOOLEAN MODE) AND deleted = 'no'";
$result=mysqli_query($link, $query);
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
      $id = $row['id'];
      $first = $row['first'];
      $last = $row['last'];
      $display = "<a onclick='liveSearchLoadClient(".$id."); liveSearchLoadDogs(".$id.");' class='liveResultLink'>".$first." ".$last." (";
      $query1 = "SELECT name FROM dogs WHERE id = $id AND deleted = 'no'";
      $result1=mysqli_query($link, $query1);
      while($row1 = mysqli_fetch_array($result1, MYSQL_ASSOC)) {
        $name = $row1['name'];
        $display = $display.$name.", ";
      }
      echo $display.")</a>";
    }
  }
  $query2 = "SELECT * FROM dogs WHERE MATCH(name, breed) AGAINST('$search' IN BOOLEAN MODE) AND deleted = 'no'";
  $result2=mysqli_query($link, $query2);
  if (mysqli_num_rows($result2) > 0) {

    while($row2 = mysqli_fetch_array($result2, MYSQL_ASSOC)) {
      $id = $row2['id'];
      $name = $row2['name'];

      $query3 = "SELECT * FROM users WHERE id = $id";
      $result3=mysqli_query($link, $query3);
      $row3 = mysqli_fetch_array($result3, MYSQL_ASSOC);
      $first = $row3['first'];
      $last = $row3['last'];
      $display = "<a class='liveResultLink'>".$first." ".$last." (";

      $query4 = "SELECT * FROM dogs WHERE id = $id AND deleted='no'";
      $result4=mysqli_query($link, $query4);
        while($row4 = mysqli_fetch_array($result4, MYSQL_ASSOC)) {
          $name = $row4['name'];
          $display = $display.$name.", ";
        }
      echo $display.")</a>";
    }

  }

  if (mysqli_num_rows($result) <= 0 && mysqli_num_rows($result2) <= 0) {

    echo "<a class='liveResultLink'>Sorry, no results.</a>";

  }



?>
