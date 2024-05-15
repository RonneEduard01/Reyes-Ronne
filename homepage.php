<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Homepage</title>
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
        <div class="text-wrapper"><a href="homepage.php">Home</a></div>
        <div class="text-wrapper"><a href="userlogin.php">User</a></div>
        <div class="text-wrapper"><a href="adminlogin.php">Admin</a></div>
        <!-- Update the link for "About Us" to point to the id "our-team" -->
        <div class="text-wrapper"><a href="#our-team">About Us</a></div>
      </div>
    </div>
  </div>

  <div class="home-banner">
    <img src="img/home-banner1.png" alt="home-banner">
  </div>
  <div class="home-content1">
    <img src="img/home-content1.png" alt="content2">
  </div>
  <div class="home-content3">
    <img src="img/home-aboutus2.png" alt="team">
  </div>
  <div id="our-team">
    <!-- Updated id to "our-team" -->
    <img src="img/home-team1.png" alt="aboutus">
  </div>
  <div class="footer">
    <img src="img/footer1.png" alt="footer">
  </div>

  <script>
    // Smooth scroll to the anchor when clicking on the link
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();

 document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });
  </script>
</body>
</html>
