<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
include("functions.php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
echo '<link rel="stylesheet" type="text/css" href="css/style.css"><script src="https://use.fontawesome.com/d08635612f.js"></script>';
echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>';
echo '<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>';
echo '<script src="js/script.js"></script>';
//Get starting date//
$date = $_GET['date'];
$date = explode("/", $date);

$day = $date[1];
$month = $date[0];
$year = $date[2];

/*Number of kennels*/
$query = "SELECT * FROM kennels";
$result=mysqli_query($link, $query);
$counter = 0;
while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $name[$counter] = $row['name'];
    $size[$counter] = $row['size'];
    $counter++;
}
$numberOfKennels = mysqli_num_rows($result);
echo "<div id='calendarPrint'>";
/*Header*/
echo '<h3 class="blueText"><i class="fa fa-calendar" aria-hidden="true"></i> Calendar</h3><hr>';
/*Kennel Div with loaded kennels*/
echo "<div id='calendarKennels'>";

loadKennels($numberOfKennels, $name, $size);

echo "</div>";

/*Week View with loaded weeks*/
echo "<div id='calendarDates'>";

goToDate($numberOfKennels, $month, $year, $day, "sevenDay");
echo "<div id='bookingBlocks'></div>";
echo "</div>";
echo "</div>";
?>
