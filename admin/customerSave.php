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
$date = date('Y-m-d');
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

/*Insert into Database*/

$query = "INSERT INTO users (dateCreated, first, last, email, phone, mobile, work, street, street2, city, province, postal, ename, ephone, notes, deleted)".
"VALUES('$date', '$first', '$last', '$email', '$phone', '$mobile', '$work', '$street', '$street2', '$city', '$province', '$postal', '$ename', '$ephone', '$notes', 'no')";
$result=mysqli_query($link, $query);
if ($result) {
    $query = "SELECT id FROM users WHERE id = LAST_INSERT_ID()";
    $result=mysqli_query($link, $query);
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result, MYSQL_ASSOC);
        $id = $row['id'];
        echo $id;
    }else{
        echo "Saved";
    }
}else{
    echo "Fail";
}
?>
