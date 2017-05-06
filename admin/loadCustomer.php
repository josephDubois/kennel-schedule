<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$id = mysqli_real_escape_string($link, $_POST['id1']);
$query = "SELECT * FROM users WHERE id='$id'";
$result=mysqli_query($link, $query);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
       $id = mysqli_real_escape_string($link, $row['id']);
       $date = mysqli_real_escape_string($link, $row['dateCreated']);
       $first = mysqli_real_escape_string($link, $row['first']);
       $last = mysqli_real_escape_string($link, $row['last']);
       $email = mysqli_real_escape_string($link, $row['email']);
       $phone = mysqli_real_escape_string($link, $row['phone']);
       $mobile = mysqli_real_escape_string($link, $row['mobile']);
       $work = mysqli_real_escape_string($link, $row['work']);
       $street = mysqli_real_escape_string($link, $row['street']);
       $street2 = mysqli_real_escape_string($link, $row['street2']);
       $city = mysqli_real_escape_string($link, $row['city']);
       $province = mysqli_real_escape_string($link, $row['province']);
       $postal = mysqli_real_escape_string($link, $row['postal']);
       $ename = mysqli_real_escape_string($link, $row['ename']);
       $ephone = mysqli_real_escape_string($link, $row['ephone']);
       $notes = mysqli_real_escape_string($link, $row['notes']);

       echo "<label>First Name:</label><br/>".
                "<input id='customerFirst' type='text' value='". $first ."'/>".
                "<label>Last Name:</label><br/>".
                "<input id='customerLast' type='text' value='". $last ."'/>".
                "<label>Email Address:</label><br/>".
                "<input id='customerEmail' type='text' value='". $email ."'/>".
                "<label>Phone Number:</label><br/>".
                "<input id='customerPhone' type='text' value='". $phone ."'/>".
                "<label>Mobile Number:</label><br/>".
                "<input id='customerMobile' type='text' value='". $mobile ."'/>".
                "<label>Work Number:</label><br/>".
                "<input id='customerWork' type='text' value='". $work ."'/>".
                "<label>Address Line 1:</label><br/>".
                "<input id='customerStreet' type='text' value='". $street ."'/>".
                "<label>Address Line 2:</label><br/>".
                "<input id='customerStreet2' type='text' value='". $street2 ."'/>".
                "<label>City:</label><br/>".
                "<input id='customerCity' type='text' value='". $city ."'/>".
                "<label>Province:</label><br/>".
                "<input id='customerProvince' type='text' value='". $province ."'/>".
                "<label>Postal Code:</label><br/>".
                "<input id='customerPostal' type='text' value='". $postal ."'/>".
                "<label>Emergency Name:</label><br/>".
                "<input id='customerEname' type='text' value='". $ename ."'/>".
                "<label>Emergency Phone Number:</label><br/>".
                "<input id='customerEphone' type='text' value='". $ephone ."'/>".
                "<label>Notes:</label><br/>".
                "<input id='customerNotes' type='text' value='". $notes ."'/>";
}else{

    echo "fail";

}

?>
