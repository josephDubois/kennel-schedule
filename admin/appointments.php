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
if(isset($_POST['date'])){
    $date= mysqli_real_escape_string($link,$_POST['date']);
    $query = "SELECT * FROM appointments WHERE INSTR(`dates`, '{$date}') > 0";
    $result=mysqli_query($link, $query);
    $rec_count = mysqli_num_rows($result);
}else{
    $date2= date("Y-m-d");
    $query = "SELECT * FROM appointments WHERE INSTR(`dates`, '{$date2}') > 0";
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
        <h3 class="floatLeft">APPOINTMENTS</h3>
        <div class="clear"></div>
        <hr>
        <form id="custSortFormArrival" class="floatLeft" method="get" action="appointments.php">
        <label class="floatLeft" for='sort'>Records</label>
        <select id="custSort" class='floatLeft' name='sort' onchange="this.form.submit();">
            <option value="5" <?php if(!isset($_GET['sort']) OR $_GET['sort'] == 5) echo "selected='selected'"; ?>>5</option>
            <option value="10" <?php if($_GET['sort'] == 10) echo "selected='selected'"; ?>>10</option>
            <option value="20" <?php if($_GET['sort'] == 20) echo "selected='selected'"; ?>>20</option>
        </select>
        </form>
        <form id='appointmentDateForm' onchange='$("#appointmentDateRangeForm").submit();' class="floatRight" method='post' action="appointments.php">
            <label class='floatLeft' for="date">Date: </label>
            <input type="date" id='date' class='floatLeft' name='date' value=<?php if (isset($_POST["date"])) { echo "'$date'"; }else{ echo "'".date("Y-m-d")."'";} ?>>
            <input id='dateRangeButton' class='noShow' type="submit"/>
        </form>
        <script>
          if ($("#date").attr("type") == "text") {
            $('#date').datepicker({
               'dateFormat': 'yy-mm-dd',
               'autoclose': true,
             });
         }
        </script>
        <div class="clear"></div>
        <table id="appointmentDataTable" class="dataTable" cellpadding='0' cellspacing='0'>
          <thead>
            <tr id='customerHeader' class="dataTableHeader">
                <td>Customer</td>
                <td>Dogs</td>
                <td>Date Booked</td>
                <td>Type</td>
                <td>Amount Owed</td>
            </tr>
          </thead>
            <?php
                if (empty($date)) {
                  //first search for arrival dates//
                  $date= date("Y-m-d");
                  $query = "SELECT * FROM appointments WHERE INSTR(`dates`, '{$date}') > 0";
                    $result=mysqli_query($link, $query);
                    $lastPageRows=mysqli_num_rows($result);
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                           $bookingid = $row['bookingid'];
                           $bookdate;
                           $bookingType;
                           $owe;
                           $clientid;
                           $dogbuttons= "";
                           $first;
                           $last;
                           $dogid = $row['dogid'];
                           //now load cost table//
                           $query2 = "SELECT * FROM costs WHERE bookingid = '$bookingid'";
                           $result2=mysqli_query($link, $query2);
                           if (mysqli_num_rows($result2) > 0) {
                             $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                             $bookdate = $row2['bookdate'];
                             $owe = $row2['total'] - $row2['deposit'];
                             $clientid = $row2['clientid'];
                             $bookingType = $row2['bookingType'];
                           }
                           //load clients name//
                           $query2 = "SELECT * FROM users WHERE id = '$clientid'";
                           $result2=mysqli_query($link, $query2);
                           if (mysqli_num_rows($result2) > 0) {
                             $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                             $first = $row2['first'];
                             $last = $row2['last'];
                           }
                           $query2 = "SELECT * FROM dogs WHERE deleted = 'no' AND dogid = '$dogid'";
                           $result2=mysqli_query($link, $query2);
                           if (mysqli_num_rows($result2) > 0) {
                             $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                               $dogid = $row2['dogid'];
                               $name = $row2['name'];
                               $dogbuttons = $dogbuttons." <button onclick=\"location.href='kennelsheet.php?dogid=<?php echo $dogid ?>';\" class='profileButton3'>$name</button>";
                           }
                          echo "<tr><td data-href='booking?bookingid=".$bookingid."'>".$first." ".$last."</td>".
                          "<td class='dogs' data-href='booking?bookingid=".$bookingid."'>".$dogbuttons."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>".$bookdate."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>".$bookingType."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>$".number_format($owe,2)."</td>";
                        }
                    }else{
                        echo "<tr><td class='sorry' colspan='5'>Sorry you have no appointments on this date.</td></tr>";
                    }
                }else{
                  $query = "SELECT * FROM appointments WHERE INSTR(`dates`, '{$date}') > 0";
                    $result=mysqli_query($link, $query);
                    $lastPageRows=mysqli_num_rows($result);
                    $left_rec = $rec_count - ($page * $rec_limit);
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                           $bookingid = $row['bookingid'];
                           $bookingType;
                           $bookdate;
                           $owe;
                           $clientid;
                           $dogbuttons= "";
                           $first;
                           $last;
                           $dogid = $row['dogid'];
                           //now load cost table//
                           $query2 = "SELECT * FROM costs WHERE bookingid = '$bookingid'";
                           $result2=mysqli_query($link, $query2);
                           if (mysqli_num_rows($result2) > 0) {
                             $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                             $bookdate = $row2['bookdate'];
                             $owe = $row2['total'] - $row2['deposit'];
                             $clientid = $row2['clientid'];
                             $bookingType = $row2['bookingType'];
                           }
                           //load clients name//
                           $query2 = "SELECT * FROM users WHERE id = '$clientid'";
                           $result2=mysqli_query($link, $query2);
                           if (mysqli_num_rows($result2) > 0) {
                             $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                             $first = $row2['first'];
                             $last = $row2['last'];
                           }
                           $query2 = "SELECT * FROM dogs WHERE deleted = 'no' AND dogid = '$dogid'";
                           $result2=mysqli_query($link, $query2);
                           if (mysqli_num_rows($result2) > 0) {
                             $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                               $dogid = $row2['dogid'];
                               $name = $row2['name'];
                               $dogbuttons = $dogbuttons." <button onclick=\"location.href='kennelsheet.php?dogid=<?php echo $dogid ?>';\" class='profileButton3'>$name</button>";
                           }
                          echo "<tr><td data-href='booking?bookingid=".$bookingid."'>".$first." ".$last."</td>".
                          "<td class='dogs' data-href='booking?bookingid=".$bookingid."'>".$dogbuttons."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>".$bookdate."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>".$bookingType."</td>".
                          "<td data-href='booking?bookingid=".$bookingid."'>$".number_format($owe,2)."</td>";
                        }
                    }else{
                        echo "<tr><td colspan='5'>Sorry you have no appointments on this date.</td></tr>";
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
