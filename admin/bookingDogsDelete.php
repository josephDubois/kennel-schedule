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
$dogid = mysqli_real_escape_string($link, $_POST['dogid1']);
$query = "UPDATE dogs SET deleted = 'yes' WHERE dogid= $dogid";
$result=mysqli_query($link, $query);
if (mysqli_affected_rows($link) > 0) {
    echo "Success";
}else{
    echo "Fail";
}

?>
