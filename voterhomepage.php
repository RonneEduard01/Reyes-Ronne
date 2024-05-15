<?php
session_start(); // Call session_start() once at the beginning of your script

@include 'db_config.php';
$eventCheckQuery = "SELECT * FROM election_info";
$result = mysqli_query($conn, $eventCheckQuery);
$count = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Homepage</title>
  <link rel="stylesheet" href="globals.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="navbar.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="voterhomepage.css?v=<?php echo time(); ?>">


</head>
  <div class="user-login">
    <div class="navigation-menu">
      <a href="voterhomepage.php">
        <img class="logo" src="img/logo.png" />
      </a>
    <div class="navbar">
      <div class="text-wrapper"><a href="voterhomepage.php">Home</a></div>
      <div class="text-wrapper"><a href="voting.php">Vote</a></div>
      <div class="text-wrapper"><a href="viewresults.php">View Results</a></div>
      <div class="text-wrapper"><a href="logout.php">Logout</a></div>
    </div>
    </div>
  </div>


  <body>
    <main>
        <?php // Assuming your session 'account' is set
        if (isset($_SESSION['account'])) {
            $account = $_SESSION['account'];

            // Prepare and execute a SQL query
            $query = "SELECT reject FROM voter_registration WHERE email = '$account'";
            $result1 = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result1);

            // Check if 'reject' is 1
            if ($row['reject'] == 1) {
                echo "<div class='elec-name'>You are rejected</div>";
            } else {
        ?>
                <div class="voterbanner">
                    <img src="img/voterbanner2.png" alt="banner">
                </div>
  <?php
  // Check if there is at least one event
  if ($count > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
     echo '<div class="elec-name">' . $row['event_name'] . ' <br><br> ' . 
        'Start Date: '.$row['event_datetimestart'] . ' <br>' .
        'End Date: '. $row['event_datetimeend'] . '</div>';
      }
?>                <div class="field">
                    <a href="voting.php">
                        <button type="button" class="button-vote" id="voteButton">Vote</button>
                    </a>
                    <a href="viewresults.php">
                        <button type="button" class="button-results" id="viewresultsButton">View Results</button>
                    </a>
                </div>
                <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php
  } else {
  ?>
        <div class="elec-name">No Election Yet</div>
<?php
  }
}}
?>

    </main>
</body>
</html>