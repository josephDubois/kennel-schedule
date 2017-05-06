<?php
session_start();
include("credentials.php");

    $username = mysqli_real_escape_string($link,$_POST['emailLogin']);
    $password = mysqli_real_escape_string($link,$_POST['passwordLogin']);
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = "Not a valid email address.".$username;
       header("Location: login.php");
       exit;
    }
    $query = "SELECT * FROM clients WHERE username='$username' AND password= '$password'";
    $result=mysqli_query($link, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result, MYSQL_ASSOC);
        $_SESSION['user'] = $row['name'];
        $_SESSION['dataBaseKey'] = $row['datakey'];
        $_SESSION['business'] = $row['business'];
        header("Location: index.php");
        exit;
    }else{
        $_SESSION['error'] = "Your username or password is incorrect!";
        header("Location: login.php");
        exit;
    }

?>
