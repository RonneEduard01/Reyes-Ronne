<?php
// Include the database configuration
@include 'db_config.php';

// Delete all existing rows from election_winner table
$deleteQuery = "DELETE FROM election_winner";
mysqli_query($conn, $deleteQuery);

// Query to select candidates from election_candidates table
$query = "SELECT * FROM election_candidates";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// Initialize counters for winners
$chairmanWinnersCount = 0;
$kagawadWinnersCount = 0;
$skChairmanWinnersCount = 0;
$skKagawadWinnersCount = 0;

// Variables to store the highest vote counts and winner names for each position
$highestChairmanVoteCount = 0;
$chairmanWinnerNames = [];

$highestKagawadVoteCount = [];
$kagawadWinnerNames = [];

$highestSKChairmanVoteCount = 0;
$skChairmanWinnerNames = [];

$highestSKKagawadVoteCount = [];
$skKagawadWinnerNames = [];

// Prepare the INSERT query
$insertQuery = "INSERT INTO election_winner SELECT * FROM election_candidates WHERE name = ? AND NOT EXISTS (SELECT 1 FROM election_winner WHERE name = ?)";
$stmt = mysqli_prepare($conn, $insertQuery);
mysqli_stmt_bind_param($stmt, "ss", $columnName, $candidateName);

while ($candidate = mysqli_fetch_assoc($result)) {
    // Check position and move winners to election_winner table
    if ($candidate['vote_count'] > $highestVoteCount) {
        // Update highest vote count and winner name for the specific position
        $highestVoteCount = $candidate['vote_count'];
        $winnerNames = [$candidate['name']];
    } elseif ($candidate['vote_count'] == $highestVoteCount) {
        // In case of a tie, add the candidate to the list of winners
        $winnerNames[] = $candidate['name'];
    }

    if ($candidate['position'] == "Chairman" && $candidate['vote_count'] > $highestChairmanVoteCount) {
        $highestChairmanVoteCount = $candidate['vote_count'];
        $chairmanWinnerNames = [$candidate['name']];
    } elseif ($candidate['position'] == "Chairman" && $candidate['vote_count'] == $highestChairmanVoteCount) {
        $chairmanWinnerNames[] = $candidate['name'];
    } elseif ($candidate['position'] == "Kagawad") {
        // Check if the candidate has a higher vote count than the lowest winner
        $minIndex = (!empty($highestKagawadVoteCount) ? array_search(min($highestKagawadVoteCount), $highestKagawadVoteCount) : false);

        // Check if $minIndex is false or not before proceeding
        if ($minIndex !== false) {
            // Your existing code for handling Kagawad winners with ties
        }        if ($candidate['vote_count'] > $highestKagawadVoteCount[$minIndex]) {
            $highestKagawadVoteCount[$minIndex] = $candidate['vote_count'];
            $kagawadWinnerNames[$minIndex] = [$candidate['name']];
        } elseif ($candidate['vote_count'] == $highestKagawadVoteCount[$minIndex]) {
            // In case of a tie, add the candidate to the list of winners
            $kagawadWinnerNames[$minIndex][] = $candidate['name'];
        }
    } elseif ($candidate['position'] == "SK Chairman" && $candidate['vote_count'] > $highestSKChairmanVoteCount) {
        $highestSKChairmanVoteCount = $candidate['vote_count'];
        $skChairmanWinnerNames = [$candidate['name']];
    } elseif ($candidate['position'] == "SK Chairman" && $candidate['vote_count'] == $highestSKChairmanVoteCount) {
        $skChairmanWinnerNames[] = $candidate['name'];
    } elseif ($candidate['position'] == "SK Kagawad") {
        // Check if the candidate has a higher vote count than the lowest winner
$minIndex = (!empty($highestSKKagawadVoteCount) ? array_search(min($highestSKKagawadVoteCount), $highestSKKagawadVoteCount) : false);

// Check if $minIndex is false or not before proceeding
if ($minIndex !== false) {
    // Your existing code for handling SK Kagawad winners with ties
}        if ($candidate['vote_count'] > $highestSKKagawadVoteCount[$minIndex]) {
            $highestSKKagawadVoteCount[$minIndex] = $candidate['vote_count'];
            $skKagawadWinnerNames[$minIndex] = [$candidate['name']];
        } elseif ($candidate['vote_count'] == $highestSKKagawadVoteCount[$minIndex]) {
            // In case of a tie, add the candidate to the list of winners
            $skKagawadWinnerNames[$minIndex][] = $candidate['name'];
        }
    }
}

// Execute the insertQuery for each winner
mysqli_stmt_bind_param($stmt, "ss", $columnName, $candidateName);

// Insert Chairman winners
foreach ($chairmanWinnerNames as $winnerName) {
    $columnName = $winnerName;
    $candidateName = $winnerName;
    mysqli_stmt_execute($stmt);
}

// Insert Kagawad winners
foreach ($kagawadWinnerNames as $winnerNames) {
    foreach ($winnerNames as $winnerName) {
        $columnName = $winnerName;
        $candidateName = $winnerName;
        mysqli_stmt_execute($stmt);
    }
}

// Insert SK Chairman winners
foreach ($skChairmanWinnerNames as $winnerName) {
    $columnName = $winnerName;
    $candidateName = $winnerName;
    mysqli_stmt_execute($stmt);
}

// Insert SK Kagawad winners
foreach ($skKagawadWinnerNames as $winnerNames) {
    foreach ($winnerNames as $winnerName) {
        $columnName = $winnerName;
        $candidateName = $winnerName;
        mysqli_stmt_execute($stmt);
    }
}


// Close the statement
mysqli_stmt_close($stmt);
?>
