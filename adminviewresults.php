<?php
session_start(); // Call session_start() once at the beginning of your script

@include 'db_config.php';
@include 'vote_count.php';

$eventCheckQuery = "SELECT * FROM election_info";
$result1 = mysqli_query($conn, $eventCheckQuery);
$count = mysqli_num_rows($result1);


$sql = "SELECT name, position, partylist, picture ,vote_count FROM election_winner ORDER BY FIELD(position, 'Chairman', 'Kagawad', 'SK Chairman','SK Kagawad')";
$result = $conn->query($sql);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voting Results</title>
    <link rel="stylesheet" href="globals.css?v=<?php echo time(); ?>">
          <link rel="stylesheet" href="voting.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="navbar.css?v=<?php echo time(); ?>">
    <style>
      * {
        margin: 0;
        padding: 0;
        font-family: sans-serif;
      }

      .chartBox {
        width: 700px;
        padding: 20px;
        border-radius: 20px;
        border: solid 3px #002137;
        background: white;
        margin: 20px auto;
        margin-bottom: 150px;
      }

      h1{
        font-family: 'Poppins', sans-serif;
        font-size: 40px;
        font-weight: 600;
        text-align: center;
        color: #002137;
      }

      body{
        background-image: url('img/voting-bg.png');
        background-size: contain;
      }
        .buttonclass{
margin-top: 12%;
  text-align: center;
  margin-bottom: 20px;
        }
        
        .buttonclass button{
    background-color: #002137;
  color: #ffffff;
  padding: 15px 50px;
  font-family: "Inter", Helvetica;
  font-size: 18px;
  border: none;
  cursor: pointer;
  border-radius: 10px;
  margin-top: 10px;
  margin-bottom: 10px;
        }
        .result{
              display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
        }
    </style>  
            <script>
    // Function to toggle visibility of add and display sections
    function toggleSections(sectionToShow) {
      const addSection = document.getElementById("graph-section");
      const displaySection = document.getElementById("result-section");

      if (sectionToShow === "add") {
        addSection.style.display = "block";
        displaySection.style.display = "none";
      } else {
        addSection.style.display = "none";
        displaySection.style.display = "block";
      }
    }

  </script>
</head>
  <body>
  <div class="navigation-menu">
      <a href="homepage.php">
        <img class="logo" src="img/logo.png" />
      </a>
      <div class="navbar">
        <div class="text-wrapper"><a href="adminhomepage.php">Home</a></div>
        <div class="text-wrapper"><a href="logout.php">Logout</a></div>
      </div>

    </div>
<?php
  if ($count < 1) {
      echo '<h1>Add Election First</h1>';
  } else {
?>    <div class="buttonclass">
    <button onclick="toggleSections('add')">View Graph Result</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <button onclick="toggleSections('display')">View Winner Result</button></div>
<?php
      while ($row = mysqli_fetch_assoc($result1)) {
     echo '<h1>' . $row['event_name'] . ' <br><br> ' . 
        'Start Date: '.$row['event_datetimestart'] . ' <br>' .
        'End Date: '. $row['event_datetimeend'] . '</h1>';
      }
?>
         <br><br><br><br>
<div class="result">
<div id="result-section" style="display: none;">
    <?php
    // Initialize a variable to store the current position
    $currentPosition = '';
    

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
        echo '<div class="candidate-name"> Vote Count: ' . $row['vote_count'] . '</div>'; 
        echo'</div>';
    }

    // Close the last "candidate-box" div if it's open
    if ($currentPosition != '') {
        echo '</div>';
    }
    ?>      
      </div> </div>
  <div id="graph-section">
    <h1>BARANGAY CHAIRMAN</h1>
    <div class="chartCard">
      <div class="chartBox">
        <canvas id="chairmanChart"></canvas>
      </div>
    </div>

    <h1>BARANGAY KAGAWAD</h1>
    <div class="chartCard">
      <div class="chartBox">
        <canvas id="kagawadChart"></canvas>
      </div>
    </div>
    
    <h1>SK CHAIRMAN</h1>
    <div class="chartCard">
        <div class="chartBox">
            <canvas id="skChairmanChart"></canvas>
        </div>
    </div>

    <h1>SK KAGAWAD</h1>
    <div class="chartCard">
        <div class="chartBox">
            <canvas id="skKagawadChart"></canvas>
        </div>
    </div>
    <br><br><br><br><br><br><br><br>


    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    
    <script>
      // Define an empty array for userData
      const chairmanData = [];
      const kagawadData = [];
      const skChairmanData = [];
      const skKagawadData = [];

      // Fetch data from the database
<?php


// Include your database configuration file
include 'db_config.php';

// Fetch data from the 'election_candidates' table where 'position' is 'Chairman'
$sql = "SELECT name, vote_count FROM election_candidates WHERE position = 'Chairman'";
$result = $conn->query($sql);

$chairmanData = [];

// Fetch data from the 'election_candidates' table where 'position' is 'Kagawad'
$sql2 = "SELECT name, vote_count FROM election_candidates WHERE position = 'Kagawad'";
$result2 = $conn->query($sql2);

$kagawadData = [];

// Fetch data from the 'election_candidates' table where 'position' is 'SK Chairman'
$sql3 = "SELECT name, vote_count FROM election_candidates WHERE position = 'SK Chairman'";
$result3 = $conn->query($sql3);

$skChairmanData = [];
    
// Fetch data from the 'election_candidates' table where 'position' is 'SK Kagawad'
$sql4 = "SELECT name, vote_count FROM election_candidates WHERE position = 'SK Kagawad'";
$result4 = $conn->query($sql4);

$skKagawadData = [];


//Get data from database 
//Chairman
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Output JavaScript code to push data into chairmanData array
        echo "chairmanData.push({ name: '" . $row['name'] . "', vote: " . $row['vote_count'] . " });\n";
    }
} else {
    echo "// No results from the database";
}

//Kagawad
if ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        // Output JavaScript code to push data into kagawadData array
        echo "kagawadData.push({ name: '" . $row['name'] . "', vote: " . $row['vote_count'] . " });\n";
    }
} else {
    echo "// No results from the database";
}

//SK Chairman
if ($result3->num_rows > 0) {
    while ($row = $result3->fetch_assoc()) {
        // Output JavaScript code to push data into skChairmanData array
        echo "skChairmanData.push({ name: '" . $row['name'] . "', vote: " . $row['vote_count'] . " });\n";
    }
} else {
    echo "// No results from the database";
}

//SK Kagawad
if ($result4->num_rows > 0) {
    while ($row = $result4->fetch_assoc()) {
        // Output JavaScript code to push data into skKagawadChart array
        echo "skKagawadData.push({ name: '" . $row['name'] . "', vote: " . $row['vote_count'] . " });\n";
    }
} else {
    echo "// No results from the database";
}

// Close the database connection
$conn->close();
?>


// Create newData array and populate it with userData
      let newchairmanData = {
        labels: [],
        datasets: [{
          label: 'Votes',
          data: [],
          backgroundColor: '#002137',
          borderColor: '#002137',
          borderWidth: 1
        }],
      };
        let newkagawadData = {
        labels: [],
        datasets: [{
          label: 'Votes',
          data: [],
          backgroundColor: '#002137',
          borderColor: '#002137',
          borderWidth: 1
        }],
      };
        let newskChairmanData = {
        labels: [],
        datasets: [{
          label: 'Votes',
          data: [],
          backgroundColor: '#002137',
          borderColor: '#002137',
          borderWidth: 1
        }],
      };
        let newskKagawadData = {
        labels: [],
        datasets: [{
          label: 'Votes',
          data: [],
          backgroundColor: '#002137',
          borderColor: '#002137',
          borderWidth: 1
        }],
      };
        
        chairmanData.map((data) => {
        newchairmanData.datasets[0].data.push(data.vote);
        newchairmanData.labels.push(data.name);
      });
        
        kagawadData.map((data) => {
        newkagawadData.datasets[0].data.push(data.vote);
        newkagawadData.labels.push(data.name);
      });
    
        skChairmanData.map((data) => {
        newskChairmanData.datasets[0].data.push(data.vote);
        newskChairmanData.labels.push(data.name);
      });
        
        skKagawadData.map((data) => {
        newskKagawadData.datasets[0].data.push(data.vote);
        newskKagawadData.labels.push(data.name);
      });

      // Chart.js initialization and configuration
      const chairmanConfig = {
        type: 'bar',
        data: newchairmanData,  
        options: {
            indexAxis: 'y',
          scale: {
            ticks: {
              precision: 0
            }
          }
        }
      };
        const kagawadConfig = {
        type: 'bar',
        data: newkagawadData,  
        options: {
            indexAxis: 'y',
          scale: {
            ticks: {
              precision: 0
            }
          }
        }
      };
        const skChairmanConfig = {
        type: 'bar',
        data: newskChairmanData,
        options: {
            indexAxis: 'y',
          scale: {
            ticks: {
              precision: 0
            }
          }
        }
      };
        
      const skKagawadConfig = {
        type: 'bar',
        data: newskKagawadData,  
        options: {
            indexAxis: 'y',
          scale: {
            ticks: {
              precision: 0
            }
          }
        }
      };
        
const chairmanChart = new Chart(
document.getElementById('chairmanChart'),
chairmanConfig
);
const kagawadChart = new Chart(
document.getElementById('kagawadChart'),
kagawadConfig
);
const skChairmanChart = new Chart(
document.getElementById('skChairmanChart'),
skChairmanConfig
);
const skKagawadChart = new Chart(
document.getElementById('skKagawadChart'),
skKagawadConfig
);
    </script>
            <?php }
      ?>
    </div>

  </body>
</html>
