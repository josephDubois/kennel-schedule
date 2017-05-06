<?php
session_start();

if (empty($_POST['dogs'])) {
  $_SESSION['error'] = "You must choose a dog.";
  header('Location: ../index.php?page=book');
}else{
  $_SESSION['selectedDogs'] = $_POST['dogs'];
  header('Location: ../index.php?page=payment');
}

?>
