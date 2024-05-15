<?php

@include 'db_config.php';

session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);

   $select = " SELECT * FROM admin_credentials WHERE username = '$email' && password = '$pass' ";
    
   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){

      $row = mysqli_fetch_array($result);
       $_SESSION['account'] = $row['username'];
       header('location:adminhomepage.php');
   }else{
      $error[] = 'incorrect email or password!';
   }
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin Login</title>
  <link rel="stylesheet" href="userlogin.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="navbar.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="globals.css?v=<?php echo time(); ?>">
  <script src="script.js"></script>
</head>
<body>
  <div class="user-login">
    <div class="navigation-menu">
      <a href="homepage.php">
          <img class="logo" src="img/logo.png" /></a>
      <div class="navbar">
        <div class="text-wrapper"><a href="homepage.php">Home</a></div>
        <div class="text-wrapper"><a href="userlogin.php">User</a></div>
        <div class="text-wrapper"><a href="adminlogin.php">Admin</a></div>
        <div class="text-wrapper"><a href="homepage.php#about-section">About Us</a></div>
      </div>
    </div>

    <div class="logos">
      <img class="logoimage" src="img/logo-1.png" />
      <p class="user-login-text" style="color: #002137; text-align: center; font-family: Inter; font-size: 50px; font-style: normal; font-weight: bold; line-height: normal;">Admin Login</p>

      <form action="" method="post">
        <div class="input-group">
          <div class="input-field">
            <input type="text" id="email" name="email" required spellcheck="false">
            <label for="email">Enter email</label>
          </div>

          <div class="input-field">
            <input type="password" id="password" name="password" required spellcheck="false">
            <label for="password">Enter password</label>
          </div>

          <button type="submit" class="login-button" name="submit" value="Login">Login</button>
<?php
if(isset($error) && !empty($error)){
    echo '<script>';
    foreach($error as $error){
        echo 'alert("' . $error . '");';
    }
    echo '</script>';
}
?>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
