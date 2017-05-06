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
$bookingid = $_SESSION['bookingId'];
    //Get date data//
    $month = sprintf("%02d", $_POST['month1']);
    $year = $_POST['year1'];
    $day = $_POST['day1'];
    $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $searchDate1 = $year."-".$month."-01";
    $searchDate2 = $year."-".$month."-".$number;
    if (!empty($day)) {
      /*Search for dates in a given month and year*/
      $searchDate1 = $year."-".$month."-".sprintf("%02d",$day);
      $sevenMoreDays = $day + 6;
      if ($sevenMoreDays > $number) {
        $diff = $sevenMoreDays - $number;
       $sevenMoreDays = $diff;
        $searchDate2 = $year."-".($month + 1)."-".sprintf("%02d", $sevenMoreDays);
      }else{
        $searchDate2 = $year."-".$month."-".sprintf("%02d", $sevenMoreDays);
      }
      $query = "SELECT * FROM overnight WHERE bookingid != '$bookingid' AND startdate BETWEEN '$searchDate1' AND '$searchDate2' OR enddate BETWEEN '$searchDate1' AND '$searchDate2' ORDER BY kennel ASC, DATE(startdate) ASC";
    }else{
      /*Search for dates in a given month and year*/
      $query = "SELECT * FROM overnight WHERE bookingid != '$bookingid' AND startdate BETWEEN '$searchDate1' AND '$searchDate2' OR enddate BETWEEN '$searchDate1' AND '$searchDate2' ORDER BY kennel ASC, DATE(startdate) ASC";
    }
    $result=mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
      /*Get data for those dates*/
      $kennel= $row['kennel'];
      $startdate= $row['startdate'];
      $enddate= $row['enddate'];
      $id = $row['id'];
      $split = $row['split'];
      $bookingid = $row['calendarid'];
      $startOfMonth;
      $endOfMonth;
      /*Find end and start of month*/
      if (!empty($day)) {
        $startOfMonth = $searchDate1;
        $endOfMonth = $searchDate2;
      }else{
        $startOfMonth = $year."-".$month."-01";
        $endOfMonth = $year."-".$month."-".$number;
      }
      $dateStamp0 = strtotime($startOfMonth);
      $dateStamp1 = strtotime($endOfMonth);
      $dateStamp2 = strtotime($startdate);
      $dateStamp3 = strtotime($enddate);
      $flipSwitch = 1;
      //first check if enddate is the same as start of month//
      if ($dateStamp3 == $dateStamp0 && !empty($day)) {
            $dogid= $row['dogsid'];
            $dogid = explode(",", $dogid);
            $kennelDate = "K".$kennel."-".$startdate."*".$id."*".$split."*".$bookingid."*";
            $dogIds = "";
            for($i = 0; $i <= count($dogid)-1; $i++) {
                $query2 = "SELECT * FROM dogs WHERE dogid=$dogid[$i]";
                $result2=mysqli_query($link, $query2);
                $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                $name = $row2['name'];
                $dogIds = $dogIds." ".$row2['dogid'];
                $kennelDate = $kennelDate." ".$name;
            }
            $kennelDate = $kennelDate."*".$dogIds."&";
            $flipSwitch = 0;
      //now check is startdate is the same as the end of the month//
    }else if($dateStamp2 == $dateStamp1 && !empty($day)){
            $dogid= $row['dogsid'];
            $dogid = explode(",", $dogid);
            $kennelDate = "K".$kennel."-".$startdate."*".$id."*".$split."*".$bookingid."*";
            $dogIds = "";
            for($i = 0; $i <= count($dogid)-1; $i++) {
                $query2 = "SELECT * FROM dogs WHERE dogid=$dogid[$i]";
                $result2=mysqli_query($link, $query2);
                $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                $name = $row2['name'];
                $dogIds = $dogIds." ".$row2['dogid'];
                $kennelDate = $kennelDate." ".$name;
            }
            $kennelDate = $kennelDate."*".$dogIds."&";
            $flipSwitch = 0;
      //now check if the booking is both before the start of month and the end of the month//
    }else if($dateStamp3 > $dateStamp1 && $dateStamp2 < $dateStamp0) {
        $begin = new DateTime($startOfMonth);
        $end = new DateTime($endOfMonth);
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
      //now check if the bookoing is longer than the end of the month//
    }else if($dateStamp3 > $dateStamp1){
        $begin = new DateTime($startdate);
        $end = new DateTime($endOfMonth);
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
        $enddate = $endOfMonth;
      //now check if the bookoing is longer than the start of the month//
    }else if($dateStamp2 < $dateStamp0) {
      if (!empty($day)) {
        $startdate = $startOfMonth;
        $begin = new DateTime($startOfMonth);
      }
        $begin = new DateTime($startdate);
        $end = new DateTime($enddate);
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
      //now for everything else in between//
    }else{
        $begin = new DateTime($startdate);
        $end = new DateTime($enddate);
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
      }
      if($flipSwitch == 1) {
         //Loop through each date in the date range and load dogs info//
         $change = 0;
         foreach($daterange as $date){
             if ($change == 0) {
                 $dogid= $row['dogsid'];
                 $dogid = explode(",", $dogid);
                 $kennelDate = "K".$kennel."-".$startdate."*".$id."*".$split."*".$bookingid."*";
                 $dogIds = "";
                 for($i = 0; $i <= count($dogid)-1; $i++) {
                     $query2 = "SELECT * FROM dogs WHERE dogid=$dogid[$i]";
                     $result2=mysqli_query($link, $query2);
                     $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                     $name = $row2['name'];
                     $dogIds = $dogIds." ".$row2['dogid'];
                     $kennelDate = $kennelDate." ".$name;
                 }
                 $kennelDate = $kennelDate."*".$dogIds."&";
                 $change = 1;
             }else{
                 $kennelDate2 = "K".$kennel."-".$date->format('Y-m-d')."*".$id."*".$split."*".$bookingid."*";
                 $kennelDate = $kennelDate.$kennelDate2."&";
             }
         }
         $kennelDate3 = "K".$kennel."-".$enddate."*".$id."*no*".$bookingid."*";
        $kennelDate = $kennelDate.$kennelDate3."&";
      }
      echo $kennelDate;
    }
?>
