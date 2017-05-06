<?php

session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}

//Variables//
$day = date("d");
$month = date("m");
$year = date("Y");
/*Number of kennels*/
$query = "SELECT * FROM kennels";
$result=mysqli_query($link, $query);
$numberOfKennels = mysqli_num_rows($result);
//start table//
echo "<table id='calendar'>";
//create Date row//
echo "<tr id='calendarDateRow'>";
$counter = 0;
for($d=1; $d<=31; $d++)
{
    $time=mktime(12, 0, 0, $month, $d, $year);
    if (date('m', $time)==$month) {
        echo "<td class='date'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
        $counter++;
    }
}
echo "</tr>";
//create needed rows//
for ($i = 1; $i <= $numberOfKennels; $i++) {
    echo "<tr>";
    for($j = $counter; $j>=1; $j--) {
        echo "<td>Test</td>";
    }
    echo "</tr>";
}
//finish table//
echo "</table>";

?>
