<?php
// Include your database connection code here
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
