<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="globals.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="adminhomepage.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="navbar.css?v=<?php echo time(); ?>">
  <title>Homepage</title>
</head>
<body>
  <div class="navigation-menu">
    <a href="adminhomepage.php">
      <img class="logo" src="img/logo.png" alt="Logo" />
    </a>
    <div class="navbar">
      <div class="text-wrapper"><a href="adminhomepage.php">Home</a></div>
      <div class="text-wrapper"><a href="logout.php">Logout</a></div>
    </div>
  </div>

  <div class="image-container">
    <div class="admin-panel">
      <span class="admin-panel-text">ADMINISTRATOR</span>
    </div>
  
    <div class="item-container">
      <div class="image-panel">
        <a href="addelection.php">
          <img src="img/admin_pic1 1.png" alt="Image 1" />
        </a>
      </div>
      <div class="textlabel">
        <a href="addelection.php" class="image-caption underline">Add Election</a>
      </div>
    </div>
  
    <div class="item-container">
      <div class="image-panel">
        <a href="addcandidates.php">
          <img src="img/admin_pic2 1.png" alt="Image 2" />
        </a>
      </div>
      <div class="textlabel">
        <a href="addcandidates.php" class="image-caption underline">Add Candidate</a>
      </div>
    </div>
  
    <div class="item-container">
      <div class="image-panel">
        <a href="voterpending.php">
          <img src="img/admin_pic3 1.png" alt="Image 3" />
        </a>
      </div>
      <div class="textlabel">
        <a href="voterpending.php" class="image-caption underline">View Voters</a>
      </div>
    </div>
  
    <div class="item-container">
      <div class="image-panel">
        <a href="adminviewresults.php">
          <img src="img/admin_pic4 1.png" alt="Image 4" />
        </a>
      </div>
      <div class="textlabel">
        <a href="adminviewresults.php" class="image-caption underline">View Results</a>
      </div>
    </div>
  </div>
</body>
</html>