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
$id = mysqli_real_escape_string($link, $_POST['id1']);
$date = mysqli_real_escape_string($link, $_POST['dateCreated']);
$first = mysqli_real_escape_string($link, $_POST['first1']);
$last = mysqli_real_escape_string($link, $_POST['last1']);
$email = mysqli_real_escape_string($link, $_POST['email1']);
$phone = mysqli_real_escape_string($link, $_POST['phone1']);
$mobile = mysqli_real_escape_string($link, $_POST['mobile1']);
$work = mysqli_real_escape_string($link, $_POST['work1']);
$street = mysqli_real_escape_string($link, $_POST['street1']);
$street2 = mysqli_real_escape_string($link, $_POST['street21']);
$city = mysqli_real_escape_string($link, $_POST['city1']);
$province = mysqli_real_escape_string($link, $_POST['province1']);
$postal = mysqli_real_escape_string($link, $_POST['postal1']);
$ename = mysqli_real_escape_string($link, $_POST['ename1']);
$ephone = mysqli_real_escape_string($link, $_POST['ephone1']);
$notes = mysqli_real_escape_string($link, $_POST['notes1']);
//Check form integrity//
if (!empty($phone) && !ctype_digit($phone)) {
        echo "customerPhone";
        exit;
}else if (!empty($ephone) && !ctype_digit($ephone)) {
        echo "customerEphone";
        exit;
}else if (!empty($mobile) && !ctype_digit($mobile)) {
        echo "customerMobile";
        exit;
}else if (!empty($work) && !ctype_digit($work)) {
        echo "customerWork";
        exit;
}else if (empty($first)) {
    echo "customerFirst";
    exit;
}else if (empty($last)) {
    echo "customerLast";
    exit;
}
if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
  echo "customerEmail";
  exit;
}
//update user in database//
$query = "UPDATE users SET first='$first', last='$last', email='$email', phone='$phone', mobile='$mobile', work='$work', street='$street', street2='$street2', city='$city', province='$province', postal='$postal', ename='$ename', ephone='$ephone', notes='$notes' WHERE id='$id'";
$result=mysqli_query($link, $query);
if (mysqli_affected_rows($link) >= 0) {
    echo "Success";
}else{
    echo "Fail";
}

?>
