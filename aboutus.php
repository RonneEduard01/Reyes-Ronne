<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>About Us</title>
  <link rel="stylesheet" href="landingpage.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="navbar.css?v=<?php echo time(); ?>"> 
  <link rel="stylesheet" href="footer.css?v=<?php echo time(); ?>">
</head>
<body>
  <div class="user-login">
    <div class="navigation-menu">
      <a href="homepage.php">
        <img class="logo" src="img/logo.png" />
      </a>
      <div class="navbar">
        <div class="text-wrapper"><a href="homepage.php?fromIndexhome=true" target="_blank">HOME</a></div>
        <div class="text-wrapper"><a href="userlogin.php">USER</a></div>
        <div class="text-wrapper"><a href="adminlogin.php">ADMIN</a></div>
        <div class="text-wrapper"><a href="aboutus.php">ABOUT US</a></div>
      </div>
    </div>
  </div>

  <div class="home-banner" id="targetAreahome">
    <img src="img/home-banner1.png" alt="home-banner">
  </div>
  <div class="home-content1">
    <img src="img/home-content1.png" alt="content2">
  </div>
  <div class="home-content2">
    <img src="img/home-aboutus2.png" alt="aboutus">
  </div>
  <div class="home-content3" id="targetArea">
    <img src="img/home-team1.png" alt="team">
  </div>
  <div class="footer">
    <img src="img/footer1.png" alt="footer">
  </div>

  <script>
    // Function to scroll to the target area on the homepage
    function scrollToTarget() {
      const targetElement = document.getElementById("targetArea");
      targetElement.scrollIntoView({ behavior: "smooth", block: "start" });
    }
    // Call the scrolling function immediately
    scrollToTarget();
  </script>

</body>
</html>
