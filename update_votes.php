<?php
session_start();

@include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the value from the session
    $accountValue = $_SESSION['account'];

    // Update the 'has_voted' column to 1 for the row where 'email' matches $accountValue
    $updateQuery = "UPDATE voter_verified SET has_voted = 1 WHERE email = '$accountValue'";
    $updateResult = mysqli_query($conn, $updateQuery);

    $votedCandidates = json_decode($_POST['votedCandidates']);

    foreach ($votedCandidates as $candidateName) {
        // Update the vote_count in the election_candidates table
        $updateQuery = "UPDATE election_candidates SET vote_count = vote_count + 1 WHERE name = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, 's', $candidateName);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // You can send a response back to the client if needed
    $response = ['success' => true, 'message' => 'Votes submitted successfully!'];
    echo json_encode($response);
} else {
    // Handle invalid requests
    header('HTTP/1.1 400 Bad Request');
    exit();
}
?>
