<?php
// Include the TCPDF library
require_once('tcpdf/library/tcpdf.php');

@include 'db_config.php';

// Functions to handle approval and rejection
function approveRegistration($conn, $email)
{
// Move the row to voter_verified table only if the email doesn't exist in voter_verified
$moveSql = "INSERT INTO voter_verified SELECT * FROM voter_registration WHERE email = '$email' 
            AND NOT EXISTS (SELECT 1 FROM voter_verified WHERE email = '$email')";
$conn->query($moveSql);

}

function checkEmailVerification($conn, $email)
{
    $checkSql = "SELECT COUNT(*) as count FROM voter_verified WHERE email = '$email'";
    $result = $conn->query($checkSql);
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

function rejectRegistration($conn, $email)
{
    // Update the reject column to true
    $updateSql = "UPDATE voter_registration SET reject = TRUE WHERE email = '$email'";
    $conn->query($updateSql);
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["approve_email"])) {
        $emailToApprove = $_POST["approve_email"];
        approveRegistration($conn, $emailToApprove);
    } elseif (isset($_POST["reject_email"])) {
        $emailToReject = $_POST["reject_email"];
        rejectRegistration($conn, $emailToReject);
    }
}

// Fetch data from the database after handling form submissions
$sql = "SELECT name_last,name_first,name_middle,name_suffix, email, date_registered FROM voter_registration WHERE reject <> 1";$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Voters Pending</title>
  <link rel="stylesheet" href="globals.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="voterpending.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="navbar.css?v=<?php echo time(); ?>">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

  <!-- Added style for the link -->
  <style>
    .action-cell a {
      text-decoration: underline;
      color: blue;
    }
  </style>
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

  <div class="content-container">
    <div class="add-election-text">VOTER'S PENDING REGISTRATION</div>

    <!-- Table for displaying election information -->
    <div class="election-table-container">
      <table border="1">
        <thead>
          <tr>
            <th>Voter's Name</th>
            <th>Voter's Email</th>
            <th>Registration Date</th>
            <th>PDF FILE</th>

            <th class="action-header" colspan="2">    Action</th>
          </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                $email = $row['email'];

                // Check if the email is not in voter_verified and reject is not true
                $isEmailVerified = checkEmailVerification($conn, $email);
                $isRejected = isset($row['reject']) && $row['reject'];

                if (!$isEmailVerified && !$isRejected) {
                    echo "<tr>";
                    echo "<td>" . $row['name_last'] . ", " . $row['name_first'] . $row['name_middle'];

                    if ($row['name_suffix'] !== 'None') {
                        echo $row['name_suffix'];
                    }

                    echo "</td>";                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['date_registered'] . "</td>";

                    // Change the button to a link
                    echo "<td class='action-cell'><a href='generate_pdf.php?email=" . $row['email'] . "' target='_blank'>View Info</a></td>";

                    echo "<td class='action-cell'><button onclick='approve(\"" . $row['email'] . "\")'>Approve</button></td>";
                    echo "<td class='action-cell'><button onclick='reject(\"" . $row['email'] . "\")'>Reject</button></td>";
                    echo "</tr>";
                }
            }
            ?>


        </tbody>
      </table>
    </div>
  </div>
<script>
function approve(email) {
    console.log('Approve clicked for email:', email);

    Swal.fire({
        title: 'Approved!',
        text: 'Registration approved successfully.',
        icon: 'success',
        showCancelButton: false,
        confirmButtonText: 'OK',
    }).then((result) => {
        if (result.isConfirmed) {
            // Set the email value and then submit the form
            document.getElementById('approve_email_input').value = email;
            document.getElementById('approve_form').submit();
        }
    });
}

    function reject(email) {
        console.log('Reject clicked for email:', email);

        Swal.fire({
            title: 'Rejected!',
            text: 'Registration rejected.',
            icon: 'error',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('reject_email_input').value = email;
                document.getElementById('reject_form').submit();
            }
        });
    }
</script>

    <!-- Hidden forms for form submission -->
    <form id="approve_form" method="post" style="display: none;">
        <input type="hidden" name="approve_email" id="approve_email_input">
    </form>

    <form id="reject_form" method="post" style="display: none;">
        <input type="hidden" name="reject_email" id="reject_email_input">
    </form>
</body>

</html>
