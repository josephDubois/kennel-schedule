<?php
session_start();
unset($_SESSION['bookingId']);
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
include("functions.php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}


include('header.php');
/*Display Today's Date*/
if (isset($_GET['calendarSelectedDates'])) {
    $date  = $_GET['calendarSelectedDates'];
    $view = $_GET['view'];
    $selectedDate= $date;
    $date = explode("/", $date);
    $day = $date[1];
    $month = $date[0];
    $year = $date[2];
}else{
    $selectedDate=date("m/d/Y");
}
?>
<div id='indexToday'>
    <?php
    echo date("l").", ".date("d M Y");
    ?>
</div>
<div id="homeContainer">
    <div id='calendarContainer'>
        <h3 class="blueText floatLeft"><i class="fa fa-calendar" aria-hidden="true"></i> Calendar</h3><a class='calendarPrint' href='printWeek.php?date=<?php echo $selectedDate; ?>' target='_blank'/><h4 class='floatRight calendarPrint'><i class="fa fa-print" aria-hidden="true"></i> Print</h4></a>
            <div class="clear"></div>
            <hr>
            <button class="floatLeft" onclick="location.href='calendar';">Show Today</button>
            <?php
                if (empty($_GET['view'])) {
            ?>
            <button class="floatLeft" onclick="$('#view').val('sevenDay'); $('#calendarForm').submit();">7 Day View</button>
            <?php
                }else{
            ?>
            <button class="floatLeft" onclick="$('#view').val(''); $('#calendarForm').submit();">Scroll View</button>
            <?php
                }
            ?>
            <form id='calendarForm' method="get" action="calendar">
                <input id='view' name='view' type="hidden" value="<?php if (isset($_GET['view'])){ echo $view; }?>" />
                <input onchange="this.form.submit();" id='calendarSelectedDates' class="floatRight" name="calendarSelectedDates" value='<?php echo $selectedDate; ?>'/>
                <label class="floatRight mobileNoShow" for='calendarSelectedDates'>Date:&nbsp;</label>
                <select class="floatRight" id='calendarService' name="calendarService">
                    <option>Kennels</option>
                    <option>Appointments</option>
                </select>
                <label class="floatRight mobileNoShow" for="calendarService">Show Service:&nbsp;</label>
            </form>
            <input type='hidden' id='currentMonth' value='<?php if (isset($_GET['calendarSelectedDates'])){ echo $month; }else{ echo date("m"); }?>' />
            <input type='hidden' id='currentYear' value='<?php if (isset($_GET['calendarSelectedDates'])){ echo $year; }else{ echo date("Y"); } ?>' />
            <div class="clear"></div>
            <div id='calendarKennels'>
                <?php
                    loadKennels($numberOfKennels, $kennelName, $size);
                ?>
            </div>
            <div id='calendarDates'>
                <?php
                    if (isset($_GET['calendarSelectedDates'])) {
                        goToDate($numberOfKennels, $month, $year, $day, $view);
                    }else{
                        loadCalendar($numberOfKennels);
                    }
                ?>
                <div id='bookingBlocks'></div>
                <div id='bookDragHighLight'></div>
                <script>
                    var nextMonth = $('#currentMonth').val();
                    var nextYear = $('#currentYear').val();
                    var lastMonth = $('#currentMonth').val();
                    var lastYear = $('#currentYear').val();
                </script>
            </div>
            <div class='bookingPopUp'>
              <p>Check In: <span id="popCheckIn"></span></p>
              <hr>
              <p>Check Out: <span id="popCheckOut"></span></p>
              <hr>
              <p id="popDogNames"></p>
              <i class='fa fa-caret-up popTri' aria-hidden='true'></i>
            </div>
            <div class="clear"></div>
    </div>
</div>

<?php
include('footer.php');
?>
