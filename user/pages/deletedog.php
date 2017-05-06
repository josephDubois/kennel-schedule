<?php
session_start();
include("../../details.php");
include("../../functions.php");

$dogid = $_POST['dogid'];
$dognumber = $_POST['dognumber'];
$_SESSION['Error'] = "";
$result = deleteDog($dogid, $link);
if (!$result){
  $_SESSION['Error'] = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>There was a problem deleting the dog.</div>';
}
header("Location: index.php?page=dog&number=".$dognumber);
?>
