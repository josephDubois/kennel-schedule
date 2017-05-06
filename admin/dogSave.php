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
$id = mysqli_real_escape_string($link,$_POST['id1']);
$name = mysqli_real_escape_string($link,$_POST['name1']);
$breed = mysqli_real_escape_string($link,$_POST['breed1']);
$color = mysqli_real_escape_string($link,$_POST['color1']);
$gender = mysqli_real_escape_string($link,$_POST['gender1']);
$age = mysqli_real_escape_string($link,$_POST['age1']);
$fixed = mysqli_real_escape_string($link,$_POST['fixed1']);
$weight = mysqli_real_escape_string($link,$_POST['weight1']);
$brand = mysqli_real_escape_string($link,$_POST['brand1']);
$amount = mysqli_real_escape_string($link,$_POST['amount1']);
$often = mysqli_real_escape_string($link,$_POST['often1']);
$vdate = mysqli_real_escape_string($link,$_POST['vdate1']);
$vname = mysqli_real_escape_string($link,$_POST['vname1']);
$vphone = mysqli_real_escape_string($link,$_POST['vphone1']);
$message = mysqli_real_escape_string($link,$_POST['message1']);
$ageDateStamp = date("Y-m-d");
//Check form integrity//
if(!ctype_digit($vphone)){
    echo "addVphone";
    exit;
}else if($vdate < date("Y-m-d")) {
    echo "addVdate";
    exit;
}else if ($fixed === "No" && $age > (8/12)) {
    echo "addFixed";
    exit;
}else if (empty($name)) {
    echo "addName";
    exit;
}
//Insert dog into database//
$query= "INSERT INTO dogs (id, name, breed, color, gender, age, ageDateStamp, fixed, weight, brand, amount, often, vdate, vet, vphone, special, deleted)
         VALUES ('$id', '$name', '$breed', '$color', '$gender', '$age', '$ageDateStamp','$fixed', '$weight', '$brand', '$amount', '$often', '$vdate', '$vname', '$vphone', '$message', 'no')";
$result=mysqli_query($link, $query);
if ($result) {
    echo "Success";
}else{
    echo "Fail";
}

?>
