<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Voting Results</title>
  <link rel="stylesheet" href="votingresults.css" />
</head>

<body>
  <div class="navigation-menu">
    <a href="adminhomepage.php">
      <img class="logo" src="img/logo.png" alt="Logo" />
    </a>
    <div class="navbar">
      <div class="text-wrapper"><a href="adminhomepage.php">HOME</a></div>
      <div class="text-wrapper"><a href="logout.php">LOGOUT</a></div>
    </div>
  </div>

  <div class="content-container">
    <div class="add-election-text">VOTING RESULTS</div>

    <!-- Table for displaying election information -->
    <div class="election-table-container">
      <table border="1">
        <thead>
          <tr>
            <th>Election Names</th>
            <th>End Date</th>
            <th class="action-header">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><a href="path/to/election-page.php" target="_blank">ElectionName</a></td>
            <td>2023-12-31</td>
            <td class="action-cell">
              <button onclick="deleteAction('election-page.php')">Delete</button>
            </td>
          </tr>

          <!-- Sample row 2 -->
          <tr>
            <td><a href="path/to/another.pdf" target="_blank">AnotherVoterID</a></td>
            <td>2023-12-31</td>
            <td class="action-cell">
              <button onclick="deleteAction('another.pdf')">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function deleteAction(fileName) {
      // Handle delete action
      alert("Delete action clicked for: " + fileName);
      // Implement logic for deletion
    }
  </script>

</body>

</html>
