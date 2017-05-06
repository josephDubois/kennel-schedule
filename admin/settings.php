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
<div class='settingsContainer'>
    <h3><i class="fa fa-cogs" aria-hidden="true"></i> Account Settings</h3>
    <hr>
    <div class="settingsColumn">
        <h1>Personal Info<hr></h1>
        <div onclick="location.href='settingsPersonal.php';" class="settingsBox">
          <h2>Kennels</h2>
          <p>Set the number, name and type of kennels you have.</p>
          <hr>
        </div>
    </div>
    <div class="settingsColumn">
        <h1>Booking Info<hr></h1>
    </div>
    <div class="settingsColumn">
        <h1>Financial Info<hr></h1>
    </div>
    <div class="settingsColumn">
        <h1>Other<hr></h1>

    </div>
</div>
<?php
include('footer.php');
?>
