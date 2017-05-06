<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
/*loop through each addtional cost and add to database*/
for ($i = 1; $i <= count($_POST['deleteChargesIds']); $i++) {
  $id = mysqli_real_escape_string($link, $_POST['deleteChargesIds'][$i - 1]);
  $query = "DELETE FROM additionalCosts WHERE id = '$id'";
  $result=mysqli_query($link, $query);
  if(mysqli_affected_rows($link) > 0) {
    echo "success";
  }else{
    echo "fail";
    exit;
  }
}

?>
