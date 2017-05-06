<?php

session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
//Collect Post Variables//
$id = mysqli_real_escape_string($link, $_POST['id']);
$query = "UPDATE users SET deleted = 'yes' WHERE id='$id'";
$result=mysqli_query($link, $query);
if (mysqli_affected_rows($link) >= 0) {
    $query = "UPDATE dogs SET deleted = 'yes' WHERE id='$id'";
    $result=mysqli_query($link, $query);
    if (mysqli_affected_rows($link) >= 0) {
        echo "Success";
    }else{
        echo "Fail";
    }
}else{
    echo "Fail";
}

?>
