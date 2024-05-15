<?php
@include 'db_config.php';


$sql = "SELECT name, position, partylist, picture FROM election_candidates ORDER BY FIELD(position, 'Chairman', 'Kagawad', 'SK Chairman', 'SK Kagawad')";
$result = $conn->query($sql);

if(isset($_POST['submit'])){
    $name=mysqli_real_escape_string($conn,$_POST['candidatename']);
    $position=mysqli_real_escape_string($conn,$_POST['candidateposition']);
    $partylist=mysqli_real_escape_string($conn,$_POST['candidatepartylist']);
    
    $picture = file_get_contents($_FILES['candidatepicture']['tmp_name']);
    
    $insert = "INSERT INTO election_candidates(name, position, partylist, picture) 
    VALUES('$name ','$position ','$partylist ',?)";
    
    $stmt = mysqli_prepare($conn, $insert);
    mysqli_stmt_bind_param($stmt, "s", $picture);
    mysqli_stmt_execute($stmt);
    header('location:addcandidates.php');
        
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// Delete action
if (isset($_POST['delete_candidate'])) {
    $name_to_delete = mysqli_real_escape_string($conn, $_POST['delete_candidate']);
    $delete_query = "DELETE FROM election_candidates WHERE name = '$name_to_delete'";
    $conn->query($delete_query);
    header('location:addcandidates.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Add Candidate</title>
  <link rel="stylesheet" href="addcandidates.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="candidatedisplay.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="navbar.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="globals.css?v=<?php echo time(); ?>">

  <script>
    // Function to toggle visibility of add and display sections
    function toggleSections(sectionToShow) {
      const addSection = document.getElementById("add-section");
      const displaySection = document.getElementById("display-section");

      if (sectionToShow === "add") {
        addSection.style.display = "block";
        displaySection.style.display = "none";
      } else {
        addSection.style.display = "none";
        displaySection.style.display = "block";
      }
    }
      function deleteCandidate(candidateName) {
    var confirmDelete = confirm("Are you sure you want to delete candidate '" + candidateName + "'?");
    if (confirmDelete) {
        document.getElementById("delete-candidate-form").delete_candidate.value = candidateName;
        document.getElementById("delete-candidate-form").submit();
    }
}

  </script>
</head>

<body>
  <div class="navigation-menu">
    <a href="adminhomepage.php">
      <img class="logo" src="img/logo.png" alt="logo" />
    </a>
    <div class="navbar">
      <div class="text-wrapper"><a href="adminhomepage.php">Home</a></div>
      <div class="text-wrapper"><a href="logout.php">Logout</a></div>
    </div>
  </div>



  
  <?php
    // Check if there is at least one value in event_name
$eventCheckQuery = "SELECT * FROM election_info";
$result1 = mysqli_query($conn, $eventCheckQuery);
$count = mysqli_num_rows($result1);
  // Check if there is at least one event
  if ($count < 1) {
      echo '<div class="add-election-container add-election-text add-election-bold">Add Election First</div>';
  } else {
  ?>
  <div class="add-election-container">
    <button onclick="toggleSections('add')">ADD CANDIDATE</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <button onclick="toggleSections('display')">DISPLAY CANDIDATE</button>

    <!-- Code for display-->
    <div id="display-section" style="display: none;">
 <form id="delete-candidate-form" action="#" method="post">
            <input type="hidden" name="delete_candidate" value="">
        </form>

<div class="container">
    <?php
    // Initialize a variable to store the current position
    $currentPosition = '';

    // Iterate through the fetched rows and print them in the table
    while ($row = $result->fetch_assoc()) {
        // Check if the position has changed
        if ($row['position'] != $currentPosition) {
          
          // If yes, print the position label with inline CSS for font size
          echo "<h2 style='font-size: 50px; padding-top: 20px;'>{$row['position']}</h2><br><br><br><br><br><br><br>";

          // Update the current position
          
          $currentPosition = $row['position'];
      }

        echo '<div class="panel"><img src="data:image/jpeg;base64,' . base64_encode($row['picture']) . '" alt="ID Picture" style="width: 100px; height: 100px;">';
        echo "<p class='name'>" . $row['name'] . "</p>";
        echo "<p>" . $row['partylist'] . "</p>";
        echo "<button onclick='deleteCandidate(\"" . $row['name'] . "\")'>Delete</button></div>";
    }
    ?>
</div>



    </div>

    <div class="add-candidates-section">

    <!-- Code for add-->
    <form id="add-section" action="#" method="post" enctype="multipart/form-data">
    <p class="user-login-text" style="color: #002137; text-align: center; font-family: Inter; font-size: 50px; font-style: normal; font-weight: bold; line-height: normal; ">ADD CANDIDATES</p><br><br>
      <label>Full Name</label>
      <input type="text" name="candidatename" placeholder="Enter Candidate Name" required /><br><br>

      <label>Select Position:</label>
      <select id="position" name="candidateposition" required>
        <option value="Chairman">Chairman</option>
        <option value="Kagawad">Kagawad</option>
        <option value="SK Chairman">SK Chairman</option>
        <option value="SK Kagawad">SK Kagawad</option>

      </select><br>

      <br><label>Party List</label>
      <input type="text" id="fullName" name="candidatepartylist" placeholder="Enter Candidate Partylist" required /><br><br><br>

      <label>Upload Picture:</label>
      <input type="file" name="candidatepicture" required>

      <div class="button-submit">
      <br><br><button type="submit" name="submit">Submit Form</button>
      </div>
    </form>
    </div>

  </div>
      <?php
  }
  ?>
</body>

</html>

