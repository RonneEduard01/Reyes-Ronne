<?php
session_start();

@include 'db_config.php';


// Fetch event name from election_info table
$eventCheckQuery = "SELECT * FROM election_info";
$eventResult = $conn->query($eventCheckQuery);
$resultinfo = mysqli_query($conn, $eventCheckQuery);
$eventInfo = mysqli_fetch_assoc($resultinfo);

$currentDateTime = date("Y-m-d H:i:s");



$sql = "SELECT name, position, partylist, picture FROM election_candidates ORDER BY FIELD(position, 'Chairman', 'Kagawad', 'SK Chairman','SK Kagawad')";
$result = $conn->query($sql);


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Voting</title>
  <link rel="stylesheet" href="globals.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="voting.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="navbar.css?v=<?php echo time(); ?>">
  <style>
    /* Add this style to set up the 3x3 grid */
    .voting-panel {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      justify-content: center;
    }
      .vote-button.voted {
  background-color: red;
  /* You can customize the styles as needed */
}
  </style>
<!-- Add this script in the head section of your HTML -->
<script>
  const maxVotesPerPosition = {
    'Chairman': 1,
    'Kagawad': 7,
    'SK Chairman': 1,
    'SK Kagawad': 7
  };

  const votesPerPosition = {};

  function toggleVoteButton(candidateName, position) {
    const voteButton = document.getElementById(candidateName);

    // Check if the maximum vote count for the position has been reached
    if (votesPerPosition[position] >= maxVotesPerPosition[position]) {
      // If reached, remove the vote for the previously voted candidate
      const prevVotedCandidate = getPrevVotedCandidate(position);
      if (prevVotedCandidate) {
        document.getElementById(prevVotedCandidate).classList.remove('voted');
        votesPerPosition[position]--;
      }
    }

    // Toggle the 'voted' class to change the color
    voteButton.classList.toggle('voted');

    // Update the vote count for the position
    votesPerPosition[position] = votesPerPosition[position] || 0;
    votesPerPosition[position] += voteButton.classList.contains('voted') ? 1 : -1;
  }

  function getPrevVotedCandidate(position) {
    // Find the previously voted candidate for the given position
    const votedCandidates = document.querySelectorAll(`.vote-button.voted[data-position="${position}"]`);
    return votedCandidates.length > 0 ? votedCandidates[0].id : null;
  }

function submitVote() {

    
  // Display a confirmation dialog
  if (!confirm('Are you sure you want to submit your vote?')) {
    return; // User clicked "Cancel"
  }

  // Create an array to store the names of the voted candidates
  const votedCandidates = [];

  // Iterate through the voted candidates and add their names to the array
  document.querySelectorAll('.vote-button.voted').forEach((voteButton) => {
    const candidateName = voteButton.id;
    votedCandidates.push(candidateName);
  });

  // Check if there are voted candidates
  if (votedCandidates.length === 0) {
    if (!confirm('You have not voted for any candidate, Are you sure you want to submit your vote?')) {
    return; // User clicked "Cancel"
  }
  }
  

  // Create a FormData object to send the voted candidates to the server
  const formData = new FormData();
  formData.append('votedCandidates', JSON.stringify(votedCandidates));

  // Use fetch API to send the data to the server using a POST request
  fetch('update_votes.php', {
    method: 'POST',
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      // Handle the response from the server
      console.log(data);

      // Show a success alert
      alert('Votes submitted successfully!');

      // Redirect to a different page (replace 'redirect_page.php' with the desired page)
      window.location.href = 'voterhomepage.php';
    })
    .catch((error) => {
      console.error('Error submitting vote:', error);

      // Show an error alert if something goes wrong
      alert('Error submitting vote. Please try again.');
    });
}


</script>

</head>
<body>
  <div class="navigation-menu">
    <a href="homepage.php">
      <img class="logo" src="img/logo.png" alt="Logo" />
    </a>
    <div class="navbar">
      <div class="text-wrapper"><a href="voterhomepage.php">Home</a></div>
      <div class="text-wrapper"><a href="voting.php">Vote</a></div>
      <div class="text-wrapper"><a href="viewresults.php">View Results</a></div>
      <div class="text-wrapper"><a href="logout.php">Logout</a></div>
    </div>
  </div>
<?php

// Get the value from the session
$accountValue = $_SESSION['account'];

// Query to check if the value exists in the 'voter_verified' column of the 'your_table' table
$checkQuery = "SELECT * FROM voter_verified WHERE email = '$accountValue'";
$result2 = mysqli_query($conn, $checkQuery);

// Check if a matching row is found
if (mysqli_num_rows($result2) < 1) {
    // The value exists in the 'voter_verified' column, you can proceed with your code here
    echo "<div class='official-election-text'>The voter is not verified. You can't proceed.</div>";
} else {
    $row = mysqli_fetch_assoc($result2);

    // Check if the has_voted column is 1
    if ($row['has_voted'] == 1) {
        // The value in column has_voted is 1, cannot proceed
        echo "<div class='official-election-text'>You Already Voted. You can't proceed.</div>";
    } else if (strtotime($currentDateTime) > strtotime($eventInfo['event_datetimeend'])) {
    echo "<div class='official-election-text'>Election Event is Already Over.</div>";
}
    else {
?>



<div class="content-container">
    <?php
    // Initialize a variable to store the current position
    $currentPosition = '';
    
        
    echo '<div class="official-election-text">' . $eventInfo['event_name'] . '</div>';

    // Iterate through the fetched rows and print them in the table
    while ($row = $result->fetch_assoc()) {
        // Check if the position has changed
        if ($row['position'] != $currentPosition) {
            // If yes, close the previous "candidate-box" div if it's open
            if ($currentPosition != '') {
                echo '</div>'; // Close the "candidate-box" div
            }
            
            // Print the position label
            echo '<div class="position-election-text"> Barangay ' . $row['position'] . '</div>';
            
            // Reset the current position and open a new "candidate-box" div
            $currentPosition = $row['position'];
            echo '<div class="voting-panel">';
        }

        // Output candidate information within "candidate-box"
        echo '<div class="candidate-box">';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['picture']) . '" alt="ID Picture" style="width: 100px; height: 100px;">';
        echo '<div class="candidate-name">' . $row['name'] . '</div>';
        echo '<div class="party-list-name">' . $row['partylist'] . 'Partylist </div>';
echo '<button id="' . $row['name'] . '" class="vote-button" data-position="' . $row['position'] . '" onclick=\'toggleVoteButton("' . $row['name'] . '", "' . $row['position'] . '")\'>Vote</button></div>';
    }

    // Close the last "candidate-box" div if it's open
    if ($currentPosition != '') {
        echo '</div>';
    }
    ?>
<div class="submit-button-container">
    <button class="submit-button" onclick="submitVote()">Submit Vote</button>
</div>

</div>

          <?php
  }}
  ?>
</body>
</html>