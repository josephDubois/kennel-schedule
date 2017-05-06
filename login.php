<?php
session_start();
include("details.php");
include("functions.php");
include("header.php");
?>
<!--SERVICES START-->
<div id="login">
  <section class="whitePanel">
    <div class="col-lg-12">
  	  <div class="section-heading">
  		 <h2>LOGIN</h2>
  		 <div class="hr"></div>
  	  </div>
  	</div>
    <p class="error">
    <?php
    $dataErr = array();
    $data = $_POST['login'];
    $user = array();
    //check if variables are empty//
    foreach ($data as $key => $value) {
      $data[$key] = test_input($value);
      $emptyResult = requiredEmpty($value);
      if (!$emptyResult) {
          $dataErr[$key] = 'empty';
      }
    }
    //check if valid email//
    if (!array_key_exists('Email Address', $dataErr)){
      $emailResult = validEmail($data['Email Address']);
      if ($emailResult === false){
          $dataErr['Email Address'] = 'email';
      }
    }
    //check if email account exists//
    if (!array_key_exists('Email Address', $dataErr)){
      $value = mysqli_real_escape_string($link, $data['Email Address']);
      $query = "SELECT id, email, password FROM users WHERE email = '$value'";
      $result = mysqli_query($link, $query);
      if(mysqli_num_rows($result) < 1) {
        $dataErr['Email Address'] = 'none';
      }else{
        $row = mysqli_fetch_array($result, MYSQL_ASSOC);
        $user['id'] = $row['id'];
        $user['email'] = $row['email'];
        $user['password'] = $row['password'];
      }
    }
    //validate passwords are equal//
    if (!array_key_exists('Email Address', $dataErr) && !array_key_exists('Password', $dataErr)){
      $passwordResult = crypt($data['Password'], $user['password']);
      if($passwordResult !== $user['password']) {
        $dataErr['Password'] = "password";
      }
    }
    //load error messages if any//
    foreach ($dataErr as $key => $value) {
      if($value === 'empty' ) {
        echo $key." cannot be empty. ";
      }else if($value === 'email') {
        echo $key." is not a valid email. ";
      }else if($value === 'none') {
        echo "The email address, password or both are incorrect.";
      }else if($value === 'password') {
        echo "The email address, password or both are incorrect.";
      }
    }
    //if error array empty send data to database//
    if (!array_key_exists('Email Address', $dataErr) && !array_key_exists('Password', $dataErr)){
      echo "Success";
      $_SESSION['user']['id'] = $user['id'];
      $_SESSION['user']['email'] = $user['email'];
      header("Location: profile/");
    }
    ?></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="loginForm" method="post">
    <div class="form-group">
      <label for="Email Address">Email Address:</label>
      <input name="login[Email Address]" type="text" class="form-control" placeholder="example@example.com" value="<?php echo $data['Email Address']; ?>">
    </div>
    <div class="form-group">
      <label for="Password">Password</label>
      <input name="login[Password]" type="password" class="form-control" placeholder="******">
    </div>
    <button type="submit" class="btn btn-default darkBlueButton">LOGIN</button>
  </form>
  <p><a href="http://ddstudio.ca/projects/FFPR3/sign-up">Sign Up</a> | <a href="http://ddstudio.ca/projects/FFPR3/forgot">Forgot your password?</a></p>
  </section>
</div>
<!--END-->
<?php include("footer.php"); ?>
