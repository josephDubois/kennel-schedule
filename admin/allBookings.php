<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
/*Pagination*/
if (!isset($_GET['sort'])) {
    $rec_limit = 5;
}else{
    $rec_limit = $_GET['sort'];
}
if( isset($_GET['page'] ) ) {
    $page = $_GET['page'] + 1;
    $offset = $rec_limit * $page ;
}else {
    $page = 0;
    $offset = 0;
}
//Customer Search bar Code//
if(isset($_POST['dateRange1'])){
    $dateRange1= mysqli_real_escape_string($link,$_POST['dateRange1']);
    $dateRange2= mysqli_real_escape_string($link,$_POST['dateRange2']);
    $query = "SELECT * FROM costs WHERE bookdate >= '$dateRange1' AND bookdate <= '$dateRange2'";
    $result=mysqli_query($link, $query);
    $rec_count = mysqli_num_rows($result);
}else{
    $date1 = date("Y-m-d");
    $date2 = date("Y-m-d");
    $query = "SELECT * FROM costs WHERE bookdate >= '$date1' AND bookdate <= '$date2'";
    $result=mysqli_query($link, $query);
    $rec_count = mysqli_num_rows($result);
    $left_rec = $rec_count - ($page * $rec_limit);
}
include('header.php');
/*Display Today's Date*/
?>
<div id='indexToday'>
    <?php
    echo date("l").", ".date("d M Y");
    ?>
</div>
<div id="homeContainer">
    <div class="customerListContainer">
        <h3 class="floatLeft">ALL BOOKINGS</h3>
        <div class="clear"></div>
        <hr>
        <form id="custSortFormArrival" class="floatLeft" method="get" action="allBookings">
          <label class="floatLeft" for='sort'>Records</label>
          <select id="custSort" class='floatLeft' name='sort' onchange="this.form.submit();">
              <option value="5" <?php if(!isset($_GET['sort']) OR $_GET['sort'] == 5) echo "selected='selected'"; ?>>5</option>
              <option value="10" <?php if($_GET['sort'] == 10) echo "selected='selected'"; ?>>10</option>
              <option value="20" <?php if($_GET['sort'] == 20) echo "selected='selected'"; ?>>20</option>
          </select>
        </form>
        <form id='arrivalDateRangeForm' class="floatLeft" method='post' action="allBookings">
            <label class='floatLeft' for="dateRange1">Date Range: </label>
            <input type="date" id='dateRange1' class='floatLeft' name='dateRange1' value=<?php if (isset($_POST["dateRange1"])) { echo "'$dateRange1'"; }else{ echo "'".date("Y-m-d")."'";} ?>>
            <div class='floatLeft to'> to </div>
            <input type="date" id='dateRange2' onchange="$('#bookingDateRangeForm').submit();" class='floatLeft' name='dateRange2' value=<?php if (isset($_POST["dateRange2"])) { echo "'$dateRange2'"; }else{ echo "'".date("Y-m-d")."'";} ?>>
            <input id='dateRangeButton' class='noShow' type="submit"/>
        </form>
        <script>
          if ($("#dateRange1").attr("type") == "text" || $("#dateRange2").attr("type") == "text") {
            $('#dateRange1').datepicker({
               'dateFormat': 'yy-mm-dd',
               'autoclose': true,
             });
             $('#dateRange2').datepicker({
                'dateFormat': 'yy-mm-dd',
                'autoclose': true,
              });
          }
        </script>
        <div class="clear"></div>
        <table id="allDataTable" class="dataTable" cellpadding='0' cellspacing='0'>
          <thead>
            <tr id='customerHeader' class="dataTableHeader">
                <td>Customer</td>
                <td>Dogs</td>
                <td>Date Booked</td>
                <td>Kennel(s)</td>
                <td>Dates</td>
                <td>Amount Paid</td>
                <td>Amount Owed</td>
            </tr>
          </thead>
            <?php
                if (empty($dateRange1) || empty($dateRange2)) {
                  $dateRange1 = date("Y-m-d");
                  $dateRange2 = date("Y-m-d");
                  //load costs//
                    $query = "SELECT * FROM costs WHERE bookdate >= '$dateRange1' AND bookdate <= '$dateRange2' AND bookingType='Over Night' LIMIT $offset, $rec_limit";
                    $result=mysqli_query($link, $query);
                    $lastPageRows=mysqli_num_rows($result);
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                           $bookingid = $row['bookingid'];
                           $bookdate = $row['bookdate'];
                           $owe = $row['total'] - $row['deposit'];
                           $deposit = $row['deposit'];
                           $type = $row['typeOfBooking'];
                           $clientid = $row['clientid'];
                           $checkin;
                           $checkout;
                           $kennel;
                           $dogbuttons= "";
                           $first;
                           $last;
                           $dogsid;
                           //load clients name//
                           $query2 = "SELECT * FROM users WHERE id = '$clientid'";
                           $result2=mysqli_query($link, $query2);
                           if (mysqli_num_rows($result2) > 0) {
                             $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                             $first = $row2['first'];
                             $last = $row2['last'];
                           }

                           $query2 = "SELECT * FROM overnight WHERE bookingid = '$bookingid'";
                           $result2=mysqli_query($link, $query2);
                           while($row2 = mysqli_fetch_array($result2, MYSQL_ASSOC)){
                             $checkin = $row2['startdate'];
                             $checkout = $row2['enddate'];
                             $kennel = $kennel.$row2['kennel'].", ";
                             $dogsid = $row2['dogsid'];
                             $dogsid = explode(",", $dogsid);
                             //now get the dogs//
                             for ($i = 0; $i <= count($dogsid) - 1; $i++) {
                               $dogsid[$i] = str_replace(" ","",$dogsid[$i]);
                               $query3 = "SELECT * FROM dogs WHERE deleted = 'no' AND dogid = '$dogsid[$i]'";
                               $result3=mysqli_query($link, $query3);
                               if (mysqli_num_rows($result2) > 0) {
                                 $row3 = mysqli_fetch_array($result3, MYSQL_ASSOC);
                                   $dogid = $row3['dogid'];
                                   $name = $row3['name'];
                                   $dogbuttons = $dogbuttons." <button onclick=\"location.href='kennelsheet.php?dogid=<?php echo $dogid ?>';\" class='profileButton3'>$name</button>";
                               }
                             }

                           }

                          echo "<tr><td data-href='booking?bookingid=".$bookingid."'>".$first." ".$last."</td>".
                          "<td class='dogs' data-href='booking?bookingid=".$bookingid."'>".$dogbuttons."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>".$bookdate."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>".$kennel."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>".$checkin." ".$checkout."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>$".number_format($deposit,2)."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>$".number_format($owe,2)."</td>";
                        }
                    }else{
                        echo "<tr><td class='sorry' colspan='7'>Sorry you have no bookings for this date range.</td></tr>";
                    }
                }else{
                  //load costs//
                    $query = "SELECT * FROM costs WHERE bookdate >= '$dateRange1' AND bookdate <= '$dateRange2' AND bookingType='Over Night' LIMIT $offset, $rec_limit";
                    $result=mysqli_query($link, $query);
                    $lastPageRows=mysqli_num_rows($result);
                    $left_rec = $rec_count - ($page * $rec_limit);
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                           $bookingid = $row['bookingid'];
                           $bookdate = $row['bookdate'];
                           $owe = $row['total'] - $row['deposit'];
                           $deposit = $row['deposit'];
                           $type = $row['typeOfBooking'];
                           $clientid = $row['clientid'];
                           $checkin;
                           $checkout;
                           $kennel = "";
                           $dogbuttons= "";
                           $first;
                           $last;
                           $dogsid;
                           //load clients name//
                           $query2 = "SELECT * FROM users WHERE id = '$clientid'";
                           $result2=mysqli_query($link, $query2);
                           if (mysqli_num_rows($result2) > 0) {
                             $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                             $first = $row2['first'];
                             $last = $row2['last'];
                           }

                           $query2 = "SELECT * FROM overnight WHERE bookingid = '$bookingid'";
                           $result2=mysqli_query($link, $query2);
                           while($row2 = mysqli_fetch_array($result2, MYSQL_ASSOC)){
                             $checkin = $row2['startdate'];
                             $checkout = $row2['enddate'];
                             $kennel = $kennel.$row2['kennel'].", ";
                             $dogsid = $row2['dogsid'];
                             $dogsid = explode(",", $dogsid);
                             //now get the dogs//
                             for ($i = 0; $i <= count($dogsid) - 1; $i++) {
                               $dogsid[$i] = str_replace(" ","",$dogsid[$i]);
                               $query3 = "SELECT * FROM dogs WHERE deleted = 'no' AND dogid = '$dogsid[$i]'";
                               $result3=mysqli_query($link, $query3);
                               if (mysqli_num_rows($result2) > 0) {
                                 $row3 = mysqli_fetch_array($result3, MYSQL_ASSOC);
                                   $dogid = $row3['dogid'];
                                   $name = $row3['name'];
                                   $dogbuttons = $dogbuttons." <button onclick=\"location.href='kennelsheet.php?dogid=<?php echo $dogid ?>';\" class='profileButton3'>$name</button>";
                               }
                             }

                           }

                          echo "<tr><td data-href='booking?bookingid=".$bookingid."'>".$first." ".$last."</td>".
                          "<td class='dogs' data-href='booking?bookingid=".$bookingid."'>".$dogbuttons."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>".$bookdate."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>".$kennel."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>".$checkin." to ".$checkout."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>$".number_format($deposit,2)."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>$".number_format($owe,2)."</td>";
                        }
                    }else{
                        echo "<tr><td colspan='7'>Sorry you have no bookings for this date range.</td></tr>";
                    }
                }
            ?>
        </table>
        <?php
        $totalPerPage = $rec_limit * ($page + 1);
            if( $left_rec <= $rec_limit) {
                if($page != 0) {
                    $lastPage = $page - 2;
                    if (isset($_GET['sort'])) {
                        echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".($totalPerPage - ($rec_limit - 1) + ($lastPageRows - 1))." of ".$rec_count." entries.</div>";
                        echo "<div class='centerPages'><div class='pageButton floatRight'>".($page + 1)."</div><a href = '$_PHP_SELF?page=$lastPage&sort=".$_GET['sort']."' class='floatRight pageButton'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                    }else{
                        echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".($totalPerPage - ($rec_limit - 1) + ($lastPageRows - 1))." of ".$rec_count." entries.</div>";
                        echo "<div class='centerPages'><div class='pageButton floatRight'>".($page + 1)."</div><a href = '$_PHP_SELF?page=$lastPage' class='floatRight pageButton'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                    }
                }
            }else if( $page == 0 && $left_rec > $rec_limit) {
                if (isset($_GET['sort'])) {
                    echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                    echo "<div class='centerPages'><a class='floatRight pageButton' href = '$_PHP_SELF?page=$page&sort=".$_GET['sort']."'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a><div class='pageButton floatRight'>".($page + 1)."</div></div>";
                }else{
                    echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                    echo "<div class='centerPages'><a class='floatRight pageButton' href = '$_PHP_SELF?page=$page'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a><div class='pageButton floatRight'>".($page + 1)."</div></div>";
                }
            }else if( $page > 0) {
                $lastPage = $page - 2;
                if (isset($_GET['sort'])) {
                    echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                    echo "<div class='centerPages'><a href = '$_PHP_SELF?page=$page&sort=".$_GET['sort']."' class='pageButton floatRight'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a>";
                    echo "<div class='pageButton floatRight'>".($page + 1)."</div>";
                    echo "<a href = '$_PHP_SELF?page=$lastPage&sort=".$_GET['sort']."' class='pageButton floatRight'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                }else{
                    echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                    echo "<div class='centerPages'><a href = '$_PHP_SELF?page=$page' class='pageButton floatRight'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a>";
                    echo "<div class='pageButton floatRight'>".($page + 1)."</div>";
                    echo "<a href = '$_PHP_SELF?page=$lastPage' class='pageButton floatRight'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                }
            }else{
                echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$rec_count." of ".$rec_count." entries.</div>";
                echo "<div class='centerPages'><div class='pageButton floatRight'>".($page + 1)."</div></div>";
            }

        ?>
        <div class="clear"></div>
    </div>
</div>
<?php
include('footer.php');
?>
