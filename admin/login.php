<!DOCTYPE html>
<?php
    session_start();
    $message = $_SESSION['error'];
?>
<html>
<head>
    <title>cloud canine v1.1.2 - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <!--Mobile Swiping-->
    <script type="text/javascript" src="js/jquery.touchSwipe.min.js"></script>
    <!--Mobile Swiping End-->
    <script src="js/script.js"></script>
    <script>
    $( document ).ready(function() {

      $("#loginButton").click(function() {
        $("#loginForm").submit();
      });

    });
    </script>
</head>

<body id='loginBody'>

<h1 class="whiteText">cloud canine<br/><span class='betaWhite'>BETA</span></h1>
<form method="post" action="loginvalidate.php" id='loginForm'>
    <h2>Login</h2>
    <br/>
    <?php
        //check to see if an error is present and display//
        if (!empty($message)) {
            echo "<p class='redText'>".$message."</p><br/>";
        }
    ?>
    <label>Username:</label>
    <input id='emailLogin' name='emailLogin' type="email" placeholder="email"/><br/><br/>
    <label>Password:</label>
    <input id='passwordLogin' name='passwordLogin' type="password" placeholder="password"/>
    <br/><br/>
    <button id="loginButton">Login</button>
</form>
<h3 class="whiteText">Version 1.1.2</h3>
<h3 class="whiteText">Developed By <a href="http://ddstudio.ca" target="_blank">DD Studio</a></h3>
</body>
</html>
<?php
    $_SESSION['error'] = '';
?>
