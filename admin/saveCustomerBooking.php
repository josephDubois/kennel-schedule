<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$customerType = mysqli_real_escape_string($link, $_POST['customerType1']);
$customerId = mysqli_real_escape_string($link, $_POST['customerId1']);
$date = date('Y-m-d');
$newClientInfo = [""];
for ($i=0; $i <= count($_POST['clientInfo1']); $i++) {
  $newClientInfo[$i] = mysqli_real_escape_string($link, $_POST['clientInfo1'][$i]);
}

//Check form integrity//
if (!empty($newClientInfo[3]) && !ctype_digit($newClientInfo[3])) {
        echo "customerPhone";
        exit;
}else if (!empty($newClientInfo[12]) && !ctype_digit($newClientInfo[12])) {
        echo "customerEphone";
        exit;
}else if (!empty($newClientInfo[4]) && !ctype_digit($newClientInfo[4])) {
        echo "customerMobile";
        exit;
}else if (!empty($newClientInfo[5]) && !ctype_digit($newClientInfo[5])) {
        echo "customerWork";
        exit;
}else if (empty($newClientInfo[0])) {
    echo "customerFirst";
    exit;
}else if (empty($newClientInfo[1])) {
    echo "customerLast";
    exit;
}
if (filter_var($newClientInfo[2], FILTER_VALIDATE_EMAIL) === false) {
  echo "customerEmail";
  exit;
}

/*Insert into or update Database*/
if ($customerType == "Old") {
  $query = "UPDATE users SET first='$newClientInfo[0]', last='$newClientInfo[1]', email='$newClientInfo[2]', phone='$newClientInfo[3]', mobile='$newClientInfo[4]', work='$newClientInfo[5]', street='$newClientInfo[6]', street2='$newClientInfo[7]', city='$newClientInfo[8]', province='$newClientInfo[9]', postal='$newClientInfo[10]'".
  ", ename='$newClientInfo[11]', ephone='$newClientInfo[12]', notes='$newClientInfo[13]' WHERE id=$customerId";
  $result=mysqli_query($link, $query);
  if (mysqli_affected_rows($link) >= 0) {
      echo $customerId;
  }else{
      echo "Fail";
  }
}else{
  $query = "INSERT INTO users (dateCreated, first, last, email, phone, mobile, work, street, street2, city, province, postal, ename, ephone, notes, deleted)".
  "VALUES('$date', '$newClientInfo[0]', '$newClientInfo[1]', '$newClientInfo[2]', '$newClientInfo[3]', '$newClientInfo[4]', '$newClientInfo[5]', '$newClientInfo[6]', '$newClientInfo[7]', '$newClientInfo[8]', '$newClientInfo[9]', '$newClientInfo[10]', '$newClientInfo[11]', '$newClientInfo[12]', '$newClientInfo[13]', 'no')";
  $result=mysqli_query($link, $query);
  if ($result) {
      $query = "SELECT id FROM users WHERE id = LAST_INSERT_ID()";
      $result=mysqli_query($link, $query);
      if(mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_array($result, MYSQL_ASSOC);
          $id = $row['id'];
          echo $id;
      }else{
          echo "Success 2s";
      }
  }else{
      echo "Fail";
  }
}

?>
