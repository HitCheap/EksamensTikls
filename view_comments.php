<?php
session_start();
include 'database.php';

if (!isset($_SESSION['id'])) {
  header('Location: Pieslegsanas/login.php');
}
?>

<?php

// Include your database connection code here
include 'database.php';

// Check if the comment ID is provided in the URL parameter
if (isset($_GET['comment_id'])) {
    // Retrieve the comment ID from the URL parameter
    $commentId = $_GET['comment_id'];

    // Perform a database query to fetch comments associated with the provided comment ID
    $sql = "SELECT * FROM komentari WHERE comment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any comments associated with the provided comment ID
    if ($result->num_rows > 0) {
        // Output comments
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row['teksts'] . "</p>";
            // You can display other comment details as needed
        }
    } else {
        // No comments found for the provided comment ID
        echo "No comments found for the specified comment ID";
        echo $commentId;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // No comment ID provided in the URL parameter
    echo "No comment ID provided";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Komentāri</title>
</head>
<body class="mx-2">
  <main class="main">
    <div class="border">
      <div class="items">
        <?php
        // Assuming $result contains the query result with comments
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row['teksts'] . "</p>";
            // You can display other comment details as needed
        }
        ?>
      </div>
    </div>
    <script>
        function atpakalIndex() {
      window.location.href = 'index.php';
    }
    </script>
  </main>
</body>
</html>
