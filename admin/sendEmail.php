<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$name = $_POST['name'];
$email = $_POST['email'];
$type = $_POST['typeOfBooking'];
if ($type == "Day Care") {
  $dates = " for ";
  for ($i = 0; $i <= count($_POST['bookingDates']) - 1; $i++) {
    $dates = $dates.$_POST['bookingDates'][$i].", ";
  }
}else{
  $dates = " from ".$_POST['bookingDates'][0]." to ".$_POST['newCheckOut'];
}

//then email file//
require 'email/PHPMailerAutoload.php';
require "email/class.phpmailer.php";
function get_include_contents($filename, $variablesToMakeLocal) {
    extract($variablesToMakeLocal);
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Set who the message is to be sent from
$mail->setFrom('booking@friendsfur-ever.ca', 'Friends Fur Ever');
//Set who the message is to be sent to
$mail->addAddress($email);
//Set the subject line
$mail->Subject = "Booking Confirmation - Friends Fur-Ever";
$variable['name'] = $name;
$variable['type'] = $type;
$variable['dates'] = $dates;
$mail->Body = get_include_contents('email/email.php', $variable);
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));//
//Replace the plain text body with one created manually
$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');//
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Success ".$dates;
}
?>
