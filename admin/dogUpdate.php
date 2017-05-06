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
$dogid = mysqli_real_escape_string($link,$_POST['dogid1']);
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
$query = "UPDATE dogs SET name='$name', breed='$breed', color='$color', age='$age', fixed='$fixed', weight='$weight', brand='$brand', amount='$amount', often='$often', vdate='$vdate', vet='$vname', vphone='$vphone', special='$message' WHERE dogid='$dogid'";
$result=mysqli_query($link, $query);
if (mysqli_affected_rows($link) > 0) {
    echo "Success";
}else{
    echo "Fail";
}

?>
