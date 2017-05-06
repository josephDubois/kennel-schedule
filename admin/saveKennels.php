<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}

$kennelsNew = mysqli_real_escape_string($link,$_POST['kennelNumber']);
if(!ctype_digit($kennelsNew)){
   $_SESSION['error'] = "Please enter a number.";
   header("Location: settingsPersonal.php");
    exit;
}
$query = "SELECT * FROM kennels";
$result=mysqli_query($link, $query);
$kennels = mysqli_num_rows($result);
echo $kennels;
//First change previous kennel settings//
for($i=1; $i <= $kennels;$i++) {
    echo "test";
    $name = mysqli_real_escape_string($link, $_POST[$i."name"]);
    $type = mysqli_real_escape_string($link, $_POST[$i."type"]);
    $query = "UPDATE kennels SET name ='$name', size='$type' WHERE id = $i";
    $result=mysqli_query($link, $query);
    if (!$result) {
        $_SESSION['error'] = "Something went wrong. - Update";
        header("Location: settingsPersonal.php");
        exit;
    }
}

//second add new kennels or subtract//
$diffrence = $kennelsNew - $kennels;

if($diffrence > 0) {
    $startNumber = $kennels + 1;
    for ($i=0; $i <= $diffrence -1; $i++) {
        $query = "INSERT INTO kennels (id, name) VALUES('".$startNumber."', 'Kennel ".$startNumber."')";
        $result=mysqli_query($link, $query);
        $startNumber++;
    }
    if($result) {
        header("Location: settingsPersonal.php");
    }else{
        header("Location: settingsPersonal.php");
        $_SESSION['error'] = "Something went wrong.".mysqli_error()."";
    }
}else if($diffrence < 0){
    for ($i=0; $i >= $diffrence + 1; $i--) {
        $query = "DELETE FROM kennels WHERE id=$kennels";
        $result=mysqli_query($link, $query);
        $kennels--;
    }
    if($result) {
        header("Location: settingsPersonal.php");
    }else{
        header("Location: settingsPersonal.php");
        $_SESSION['error'] = "Something went wrong.".mysqli_error()."";
    }
}else{
    header("Location: settingsPersonal.php");
}

?>
