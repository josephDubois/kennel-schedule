<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
include('header.php');
/*Display Today's Date*/
?>
<div id='indexToday'>
    <?php
    echo date("l").", ".date("d M Y");
    ?>
</div>
<input id="limitNumber" type="hidden" value="5"/>
<div id="homeContainer">
  <div class="dataLeft">
    <div id='arrivalContainer' class="dataContainer">
      <input id='arrivalDate' type='hidden' value='<?php echo date("d m Y"); ?>'/>
        <h3 class="floatLeft">Arrivals For <span id='arrivalTime'>Today</span></h3>
        <div id='arrivalNext' class="arrowBorder floatRight"><i class="fa fa-chevron-right" aria-hidden="true"></i></div><div id='arrivalBack' class="arrowBorder floatRight"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
        <div class="clear"></div>
        <hr>

        <div id='arrivalsLoaded' class="loadingBox">
          <script>
            loadContainerBookings($('#arrivalDate').val(), 'arrival');
          </script>
       </div>
       <hr>
       <a href='arrivals'>View All Arrivals...</a>
       <div id="arrivalLoading" class="loading"><i class="fa fa-spinner fa-pulse" aria-hidden="true"></i></div>
    </div>
    <div id='departureContainer' class="dataContainer">
        <input id='departureDate' type='hidden' value='<?php echo date("d m Y"); ?>'/>
        <h3 class="floatLeft">Departures For <span id='departureTime'>Today</span></h3>
        <div id='departureNext' class="arrowBorder floatRight"><i class="fa fa-chevron-right" aria-hidden="true"></i></div><div id='departureBack' class="arrowBorder floatRight"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
        <div class="clear"></div>
        <hr>

        <div id='departuresLoaded' class="loadingBox">
          <script>
            loadContainerBookings($('#departureDate').val(), 'departure');
          </script>
       </div>
       <hr>
       <a href='departures'>View All Departures...</a>
       <div id="departureLoading" class="loading"><i class="fa fa-spinner fa-pulse" aria-hidden="true"></i></div>
    </div>
    <div id="appointmentContainer" class="dataContainer">
      <input id='appointmentDate' type='hidden' value='<?php echo date("d m Y"); ?>'/>
      <h3 class="floatLeft">Appointments For <span id='appointmentTime'>Today</span></h3>
      <div id='appointmentNext' class="arrowBorder floatRight"><i class="fa fa-chevron-right" aria-hidden="true"></i></div><div id='appointmentBack' class="arrowBorder floatRight"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
      <div class="clear"></div>
      <hr>

      <div id='appointmentsLoaded' class="loadingBox">
        <script>
          loadContainerBookings($('#appointmentDate').val(), 'appointment');
        </script>
     </div>
     <hr>
     <a href='appointments'>View All Appointments...</a>
     <div id="appointmentLoading" class="loading"><i class="fa fa-spinner fa-pulse" aria-hidden="true"></i></div>
    </div>
  </div>
  <div class="dataRight">
    <div id="latestContainer" class="dataContainer">
      <input id='latestDate' type='hidden' value='<?php echo date("d m Y"); ?>'/>
      <h3 class="floatLeft">Latest Bookings</h3>
      <div class="clear"></div>
      <hr>

      <div id='latestLoaded' class="loadingBox">
        <table id="bookingHistoryTable" class="departmentTable" cellpadding='0' cellspacing='0'>
          <thead>
            <tr id='bookingHistoryHeader' class="departmentHeader">
                <td width='40%'>Customer</td>
                <td>Dates</td>
                <td>Type</td>
            </tr>
          </thead>
            <?php
            $query = "SELECT * FROM costs ORDER BY bookdate DESC LIMIT 5";
            $result=mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                $bookingid = $row['bookingid'];
                $type = $row['bookingType'];
                $clientid = $row['clientid'];
                if ($type == "Over Night") {
                  $table = "overnight";
                }else{
                  $table = "appointments";
                }
                $bookingid = $row['bookingid'];
                $query2 = "SELECT * FROM users WHERE id = '$clientid'";
                $result2=mysqli_query($link, $query2);
                $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                $first = $row2['first'];
                $last = $row2['last'];
                echo "<tr><td onclick=\"location.href='booking?bookingid=".$bookingid."';\"'>".$first." ".$last."</td>";

                $query2 = "SELECT * FROM $table WHERE bookingid = '$bookingid'";
                $result2=mysqli_query($link, $query2);
                if ($table == "overnight"){
                  $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                    $checkin = $row2['startdate'];
                    $checkout = $row2['enddate'];
                    echo "<td data-href='booking.php?bookingid=".$id."'>".$checkin." to ".$checkout."</td>";
                }else{
                  $counter = 0;
                  $realDates = array();
                  while($row2 = mysqli_fetch_array($result2, MYSQL_ASSOC)) {
                    $dates[$counter] = $row2['dates'];
                    $counter++;
                  }
                  for($i = 0; $i <= count($dates) - 1; $i++) {
                    $tempDates = explode("*", $dates[$i]);
                    for ($j = 0; $j <= count($tempDates) - 2; $j++){
                      $answer = in_array($tempDates[$j],$realDates);
                      if ($answer == FALSE) {
                        array_push($realDates,$tempDates[$j]);
                      }
                    }
                  }
                  echo "<td data-href='booking.php?bookingid=".$id."'>".$realDates[0]." to ".$realDates[count($realDates)-1]."</td>";
                }
                echo "<td data-href='booking.php?bookingid=".$id."'>".$type."</td>";
              }
            }else{
                echo "<tr><td colspan='4'>There are no latest bookings yet.</td></tr>";
            }

            ?>

        </table>
     </div>
     <hr>
     <a href='allBookings'>View All Bookings...</a>
   </div>
    <div id='outstandingContainer' class="dataContainer">
      <h3 class="floatLeft">Outstanding Payments</h3>
      <div class="clear"></div>
      <hr>

      <div id='outstandingLoaded' class="loadingBox">
        <table id="outstandingPaymentsTable" class="departmentTable" cellpadding='0' cellspacing='0'>
          <thead>
            <tr id='outstandingPaymentsHeader' class="departmentHeader">
                <td width='25%'>Customer</td>
                <td width='25%'>Booking Date</td>
                <td width='25%'>Type</td>
                <td width='25%'>Amount Owing</td>
            </tr>
          </thead>
            <?php
            $query = "SELECT * FROM costs WHERE paid = '0' ORDER BY bookdate ASC LIMIT 5";
            $result=mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                $bookingid = $row['bookingid'];
                $type = $row['bookingType'];
                $bookDate =  $row['bookdate'];
                $clientid = $row['clientid'];
                $owe = $row['total'] - $row['deposit'];
                $query2 = "SELECT * FROM users WHERE id = '$clientid'";
                $result2=mysqli_query($link, $query2);
                $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                $first = $row2['first'];
                $last = $row2['last'];
                echo "<tr><td onclick=\"location.href='booking?bookingid=".$bookingid."';\"'>".$first." ".$last."</td>".
                "<td onclick=\"location.href='booking?bookingid=".$bookingid."';\"'>".$bookDate."</td>".
                "<td onclick=\"location.href='booking?bookingid=".$bookingid."';\"'>".$type."</td>".
                "<td onclick=\"location.href='booking?bookingid=".$bookingid."';\"'>$".number_format($owe,2)."</td>";
              }
            }else{
                echo "<tr><td colspan='4'>There are no outstanding payments.</td></tr>";
            }

            ?>

        </table>
     </div>
     <hr>
     <a href="#">View All Outstanding Payments...</a>
    </div>

  </div>
  <div class='clear'></div>
</div>
<?php
include('footer.php');
?>
