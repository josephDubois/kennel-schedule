<!DOCTYPE html>
<html>
<head>
    <title>Cloud Canine v1.1.2</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="cleartype" content="on">
    <meta name="MobileOptimized" content="320">
    <meta name="HandheldFriendly" content="True">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" sizes="120x120" href="images/apple-touch-icon.png">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.min.css">
    <link rel="stylesheet" href="css/chosen.min.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!--Mobile Swiping-->
    <script type="text/javascript" src="js/jquery.touchSwipe.min.js"></script>
    <!--Mobile Swiping End-->
    <script src="js/script.min.js"></script>
    <script src="js/jquery-scrollto.js"></script>
</head>

<body class="bodyMove">
    <header>
    <h1 id="businessName" class="floatLeft"><?php echo $_SESSION['business'];?> <span class="beta">BETA</span></h1>
    <span id="auxMenuFull"><a href='logout.php' class="floatRight"><i class="fa fa-power-off" aria-hidden="true"></i></a><a href='settings.php' class="floatRight"><i class="fa fa-cog" aria-hidden="true"></i></a><!--<a href='settings.php' class="floatRight"><i class="fa fa-bell" aria-hidden="true"></i></a>--></span>
    <div class="clear"></div>
    </header>
    <nav id="fullNav">
        <ul id="mainNav">
            <li class="index"><a href='index'>Home</a></li>
            <li class="customers"><a href='customers?sort=5'>Customers</a></li>
            <li class="reports">Reports <i class="fa fa-angle-down" aria-hidden="true"></i>
              <div id='reportsDrop'>
                <a href='arrivals'>Arrivals</a>
                <a href='departures'>Departures</a>
                <a href='appointments'>Appointments</a>
                <a href='allBookings'>All Bookings</a>
                <!--<a href='#'>Vaccines Expired</a>
                <a href='#'>Feeding</a>
                <a href='outstanding'>Outstanding Payments</a>-->
              </div>
            </li>
            <li class="calendar"><a href='calendar'>Calendar</a></li>
            <!--<li class="appointments">Appointments</li>-->
        </ul>
        <!--<input id='uniSearch' type='text' placeholder='Search'/>-->
        <button onclick="location.href='booking';">Create a Booking</button>
    </nav>
    <div class="mobileNavIcon"><i class="fa fa-bars" aria-hidden="true"></i></div>
    <nav id="mobileNav" class="mobileNav">
      <ul id="mobileNavList">
          <li class="index activeMobile"><a href='index'>Home</a></li>
          <li class="customers"><a href='customers?sort=5'>Customers</a></li>
          <li class="reports reportsMobile">Reports <i class="fa fa-angle-down" aria-hidden="true"></i></li>
          <ul class='reportsDrop'>
            <li><a href='arrivals'>Arrivals</a></li>
            <li><a href='departures'>Departures</a></li>
            <li><a href='appointments'>Appointments</a></li>
            <li><a href='allBookings'>All Bookings</a></li>
            <!--<a href='#'>Vaccines Expired</a>
            <a href='#'>Feeding</a>
            <li><a href='outstanding'>Outstanding Payments</a></li>-->
          </ul>
          <li class="calendar haveToMove"><a href='calendar'>Calendar</a></li>
          <li class="booking"><a href='booking'>Create a Booking</a></li>
          <!--<li class="appointments">Appointments</li>-->
      </ul>
      <div id="mobileToolBar">
        <div class="tab"><a href="settings"><i class="fa fa-cog" aria-hidden="true"></i></a></div>
        <div class="tab"><a href="logout"><i class="fa fa-power-off" aria-hidden="true"></i></a></div>
      </div>
    </nav>
