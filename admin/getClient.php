<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}

$id = $_POST['id1'];
$query = "SELECT * FROM users WHERE id = $id";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result, MYSQL_ASSOC);
$first = $row['first'];
$last = $row['last'];
$email = $row['email'];
$phone = $row['phone'];
$mobile = $row['mobile'];
$work = $row['work'];
$street = $row['street'];
$street2 = $row['street2'];
$city = $row['city'];
$province = $row['province'];
$postal = $row['postal'];
$ename = $row['ename'];
$ephone = $row['ephone'];
$notes = $row['notes'];

echo $first."&".$last."&".$email."&".$phone."&".$mobile."&".
$work."&".$street."&".$street2."&".$city."&".$province."&".
$postal."&".$ename."&".$ephone."&".$notes;

?>
