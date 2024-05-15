<?php
@include 'db_config.php';

// Check if there is at least one value in event_name
$eventCheckQuery = "SELECT * FROM election_info";
$result = mysqli_query($conn, $eventCheckQuery);
$count = mysqli_num_rows($result);

if(isset($_POST['submit'])){
   $eventname = mysqli_real_escape_string($conn, $_POST['name']);
   $startdate = mysqli_real_escape_string($conn, $_POST['start']);
   $enddate = mysqli_real_escape_string($conn, $_POST['end']);

    $insert = "INSERT INTO election_info(event_name, event_datetimestart, event_datetimeend) VALUES('$eventname ','$startdate ','$enddate')";
          
    mysqli_query($conn, $insert);
    header('location:adminhomepage.php');
    mysqli_close($conn);
};

// Handle delete request
if(isset($_GET['delete_event'])){
    $deleteEventName = mysqli_real_escape_string($conn, $_GET['delete_event']);
    $deleteQuery = "DELETE FROM election_info WHERE event_name = '$deleteEventName'";
    mysqli_query($conn, $deleteQuery);
    header('location:adminhomepage.php');
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Election</title>
  <link rel="stylesheet" href="globals.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="addelection.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="navbar.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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

  <?php
  // Check if there is at least one event
  if ($count > 0) {
      echo '<div class="add-election-container add-election-text add-election-bold">Election currently running</div>';
      while ($row = mysqli_fetch_assoc($result)) {
     echo '<div class="add-election-container add-election-done">' . $row['event_name'] . ' <br><br> ' . 
        'Start Date: '.$row['event_datetimestart'] . ' <br>' .
        'End Date: '. $row['event_datetimeend'] . ' | <a href="?delete_event=' . urlencode($row['event_name']) . '">Delete</a></div>';
      }
      //edit mo nlng ung .add-election-done
  } else {
  ?>
  <div class="add-election-container">
    <div class="add-election-text add-election-bold">ADD ELECTION</div>
    <form action="#" method="post">
    <div class="field">
      <label for="electionName">Election Name:</label>
      <input type="text" id="electionName" placeholder="Enter election name" name="name" required>
    </div>

    <div class="field">
      <label for="startDate">Start Date:</label>
        <input class="date-start" type="datetime-local" placeholder="Select DateTime" name="start" required>
    </div>

    <div class="field">
      <label for="endDate">End Date:</label>
        <input class="date-end" type="datetime-local" placeholder="Select DateTime" name="end" required>
    </div>
    <div class="field">
      <button type="submit" class="submit-button" id="submitButton" name="submit">Submit</button>
    </div>  
    </form>
  </div>
  <?php
  }
  ?>

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script>
       config = {
      altInput: true,
      altFormat: "F j, Y",
      dateFormat: "Y-m-d",
  } document.addEventListener('DOMContentLoaded', function () {
      flatpickr("input[type='datetime-local']", {});
    });
  </script>
</body>
</html>