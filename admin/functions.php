<?php
session_start();
$_SESSION['sevenDays'];
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
/*Get Kennel Info*/
  $query = "SELECT * FROM kennels";
  $result=mysqli_query($link, $query);
  $counter = 0;
  while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
      $kennelName[$counter] = $row['name'];
      $size[$counter] = $row['size'];
      $counter++;
  }
  $numberOfKennels = mysqli_num_rows($result);

/*Number Of Days In a Month*/
function numberOfDays ($month, $year) {
  $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  return $numberOfDays;
}

function loadCalendar($numberOfKennels) {
    //Variables//
$day = date("d");
$month = date("m");
$year = date("Y");
//start table//
echo "<table id='calendar'>";
//create Date row//
echo "<tr id='calendarDateRow'>";
$counter = 0;
for($d=1; $d<=31; $d++)
{
    $time=mktime(12, 0, 0, $month, $d, $year);
    if (date('m', $time)==$month && date('d', $time)==$day && date('Y', $time)==$year) {
        echo "<td id='scrollToday' class='date today'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
        $counter++;
    }else if (date('m', $time)==$month) {
        echo "<td class='date'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
        $counter++;
    }
}
echo "</tr>";
//create needed rows//
for ($i = 1; $i <= $numberOfKennels; $i++) {
    echo "<tr id='kennel".$i."'>";
    for($j = 1; $j<=$counter; $j++) {
        $url = "booking?kennel=".$i."&day=".$j."&month=".$month."&year=".$year;
        if ($j === (int)$day) {
            echo "<td id='K".$i."-".$year."-".$month."-".sprintf("%02d", $j)."' class='today calendarTD'><button class='bookButton' onclick=\"location.href='$url';\">Book</button></td>";
        }else{
            echo "<td id='K".$i."-".$year."-".$month."-".sprintf("%02d", $j)."' class='calendarTD ui-widget-header'><button class='bookButton' onclick=\"location.href='$url';\";'>Book</button></td>";
        }
    }
    echo "</tr>";
}
//finish table//
echo "</table>";
echo "<script>$('#scrollToday').ScrollTo();loadBookings(".$month.", ".$year.")</script>";
}

function loadSplitCalendar($numberOfKennels) {
  if (!empty($_SESSION['splitCalendarDate'])) {
    $time = strtotime($_SESSION['splitCalendarDate']);
    $day = date('d',$time);
    $month = date('m',$time);
    $year = date('Y',$time);
  }else{
    $day = date("d");
    $month = date("m");
    $year = date("Y");
  }
//start table//
echo "<table id='calendar'>";
//create Date row//
echo "<tr id='calendarDateRow'>";
$counter = 0;
for($d=1; $d<=31; $d++)
{
    $time=mktime(12, 0, 0, $month, $d, $year);
    if (date('m', $time)==$month && date('d', $time)==$day && date('Y', $time)==$year) {
        echo "<td id='scrollToday' class='date today'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
        $counter++;
    }else if (date('m', $time)==$month) {
        echo "<td class='date'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
        $counter++;
    }
}
echo "</tr>";
//create needed rows//
for ($i = 1; $i <= $numberOfKennels; $i++) {
    echo "<tr id='kennel".$i."'>";
    for($j = 1; $j<=$counter; $j++) {
        $url = "booking?kennel=".$i."&day=".$j."&month=".$month."&year=".$year;
        if ($j === (int)$day) {
            echo "<td id='K".$i."-".$year."-".$month."-".sprintf("%02d", $j)."' onclick='splitDateSelected($(this).attr(\"id\"));' class='today calendarTD calendarClick'></td>";
        }else{
            echo "<td id='K".$i."-".$year."-".$month."-".sprintf("%02d", $j)."' onclick='splitDateSelected($(this).attr(\"id\"));' class='calendarTD calendarClick'></td>";
        }
    }
    echo "</tr>";
}
//finish table//
echo "</table>";
echo "<script>$('#scrollToday').ScrollTo(); loadBookings(".$month.", ".$year.");</script>";
}

function loadNextMonth($numberOfKennels, $month, $year) {
        //Variables//
    $month = sprintf("%02d", $month);
    $day = date("d");
    $counter = 0;
    for($d=1; $d<=31; $d++)
    {
        $time=mktime(12, 0, 0, $month, $d, $year);
        if (date('m', $time)==$month) {
            echo "<td class='date'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
            $counter++;
        }
    }
    echo "*";
    //create needed rows//
    for ($i = 1; $i <= $numberOfKennels; $i++) {
        for($j = 1; $j<=$counter; $j++) {
             $url = "booking?kennel=".$i."&day=".$j."&month=".$month."&year=".$year;
                echo "<td id='K".$i."-".$year."-".$month."-".sprintf("%02d", $j)."' onmouseover=\"$(this).find('.bookButton').show();\" onmouseleave=\"$(this).find('.bookButton').hide();\" class='calendarTD'><button class='bookButton' onclick=\"location.href='$url';\">Book</button></td>";
        }
        echo "*";
    }
}
function loadNextSplitMonth($numberOfKennels, $month, $year) {
        //Variables//
    $month = sprintf("%02d", $month);
    $day = date("d");
    $counter = 0;
    for($d=1; $d<=31; $d++)
    {
        $time=mktime(12, 0, 0, $month, $d, $year);
        if (date('m', $time)==$month) {
            echo "<td class='date'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
            $counter++;
        }
    }
    echo "*";
    //create needed rows//
    for ($i = 1; $i <= $numberOfKennels; $i++) {
        for($j = 1; $j<=$counter; $j++) {
             $url = "booking?kennel=".$i."&day=".$j."&month=".$month."&year=".$year;
                echo "<td id='K".$i."-".$year."-".$month."-".sprintf("%02d", $j)."' onclick='splitDateSelected($(this).attr(\"id\"));' class='calendarTD calendarClick ui-widget-header'></td>";
        }
        echo "*";
    }
}

function loadLastMonth($numberOfKennels, $month, $year) {
        //Variables//
    $month = sprintf("%02d", $month);
    $day = date("d");
    $counter = 0;
    for($d=1; $d<=31; $d++)
    {
        $time=mktime(12, 0, 0, $month, $d, $year);
        if (date('m', $time)==$month) {
            echo "<td class='date'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
            $counter++;
        }
    }
    echo "*";
    //create needed rows//
    for ($i = 1; $i <= $numberOfKennels; $i++) {
        for($j = 1; $j<=$counter; $j++) {
            $url = "booking?kennel=".$i."&day=".$j."&month=".$month."&year=".$year;
                echo "<td id='K".$i."-".$year."-".$month."-".sprintf("%02d", $j)."' onmouseover=\"$(this).find('.bookButton').show();\" onmouseleave=\"$(this).find('.bookButton').hide();\" class='calendarTD'><button class='bookButton' onclick=\"location.href='$url';\">Book</button></td>";
        }
        echo "*";
    }
}
function loadLastSplitMonth($numberOfKennels, $month, $year) {
        //Variables//
    $month = sprintf("%02d", $month);
    $day = date("d");
    $counter = 0;
    for($d=1; $d<=31; $d++)
    {
        $time=mktime(12, 0, 0, $month, $d, $year);
        if (date('m', $time)==$month) {
            echo "<td class='date'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
            $counter++;
        }
    }
    echo "*";
    //create needed rows//
    for ($i = 1; $i <= $numberOfKennels; $i++) {
        for($j = 1; $j<=$counter; $j++) {
            $url = "booking?kennel=".$i."&day=".$j."&month=".$month."&year=".$year;
                echo "<td id='K".$i."-".$year."-".$month."-".sprintf("%02d", $j)."' onclick='splitDateSelected($(this).attr(\"id\"));' class='calendarTD calendarClick ui-widget-header'></td>";
        }
        echo "*";
    }
}

function goToDate($numberOfKennels, $month, $year, $day, $view) {
    $month = sprintf("%02d", $month);
    if (!empty($view)) {
        //start table//
        echo "<table id='calendar'>";
        //create Date row//
        echo "<tr id='calendarDateRow'>";
        $counter = 0;
        $sevenMore = $day + 6;
        for($d = $day; $d <= $sevenMore; $d++){
            $time=mktime(12, 0, 0, $month, $d, $year);
            if (date('m', $time)==$month && date('d', $time)==$day && date('Y', $time)==$year) {
                echo "<td id='scrollToday' class='date today'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
                $counter++;
            }else{
                echo "<td class='date'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
                $counter++;
            }
        }
        echo "</tr>";
        //create needed rows//
        //check to see if it should go to the next month//
        $d = $day;
        $m = $month;
        for ($i = 1; $i <= $numberOfKennels; $i++) {
            $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            echo "<tr id='kennel".$i."'>";
            for($j = 1; $j<=$counter; $j++) {
                if($d > $number){
                  $diff = $d - $number;
                  $d = 1;
                  $m++;
                  $number = cal_days_in_month(CAL_GREGORIAN, $m, $year);
                }
                if ($j === 1) {
                    echo "<td id='K".$i."-".$year."-".$m."-".sprintf("%02d", $d)."' class='today'></td>";
                }else{
                    echo "<td id='K".$i."-".$year."-".$m."-".sprintf("%02d", $d)."'></td>";
                }
                $d++;
            }
            $d = $day;
            $m = $month;
            echo "</tr>";
        }
        //finish table//
        echo "</table>";
        echo "<script>loadBookings(".$month.", ".$year.", ".$day.")</script>";

    }else{
        //start table//
        echo "<table id='calendar'>";
        //create Date row//
        echo "<tr id='calendarDateRow'>";
        $counter = 0;
        for($d=1; $d<=31; $d++)
        {
            $time=mktime(12, 0, 0, $month, $d, $year);
            if (date('m', $time)==$month && date('d', $time)==$day && date('Y', $time)==$year) {
                echo "<td id='scrollToday' class='date today'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
                $counter++;
            }else if (date('m', $time)==$month) {
                echo "<td class='date'>".date('D', $time)."<br/>".date('d M y', $time)."</td>";
                $counter++;
            }
        }
        echo "</tr>";
        //create needed rows//
        for ($i = 1; $i <= $numberOfKennels; $i++) {
            echo "<tr id='kennel".$i."'>";
            for($j = 1; $j<=$counter; $j++) {
                if ($j === (int)$day) {
                    echo "<td id='K".$i."-".$year."-".$month."-".sprintf("%02d", $j)."' class='today'></td>";
                }else{
                    echo "<td id='K".$i."-".$year."-".$month."-".sprintf("%02d", $j)."'></td>";
                }
            }
            echo "</tr>";
        }
        //finish table//
        echo "</table>";
        echo "<script>$('#scrollToday').ScrollTo(); loadBookings(".$month.", ".$year.")</script>";
    }

}

function loadKennels($numberOfKennels, $name, $size) {
    echo "<table>";
    echo "<tr><td>Kennels</td></tr>";
    for ($i = 1; $i <= $numberOfKennels; $i++) {
        echo "<tr><td>".$name[$i-1]."</td></tr>";
    }

    echo "</table>";

}
?>
