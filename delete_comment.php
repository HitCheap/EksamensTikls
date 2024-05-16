<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
    exit();
}

// Check if the comment ID is provided in the request
if (isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];

    // Your database connection code here
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'majaslapa';
  
    $conn = new mysqli($host, $username, $password, $database);
    

    // Prepare and execute the DELETE statement
    $stmt = $conn->prepare("DELETE FROM komentari WHERE comment_id = ?");
    $stmt->bind_param('i', $comment_id);

    if ($stmt->execute()) {
        // Successful deletion
        echo json_encode(['success' => true]);
    } else {
        // Error in deletion
        echo json_encode(['error' => 'Error deleting the comment']);
    }

    $stmt->close();
    $conn->close();
} else {
    // Comment ID not provided
    echo json_encode(['error' => 'Comment ID not provided']);
}
?>
