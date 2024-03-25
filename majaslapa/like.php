<?php
session_start();

// Database connection code (similar to your other PHP files)
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Datubāzes pieslēgums neveiksmīgs: ' . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$userID = $_SESSION['id'];

// Check if the user already liked
// You need to implement your logic here based on your database structure

// Assuming you have a table 'likes_table' with columns 'user_id' and 'post_id'
$postID = $_POST['post_id'];  // You need to pass post_id from your JavaScript

// Check if the user already liked the post
$sql = $conn->prepare("SELECT * FROM likes_table WHERE user_id = ? AND post_id = ?");
$sql->bind_param("ii", $userID, $postID);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    // User already liked, remove the like
    $deleteSQL = $conn->prepare("DELETE FROM likes_table WHERE user_id = ? AND post_id = ?");
    $deleteSQL->bind_param("ii", $userID, $postID);
    $deleteSQL->execute();
    echo json_encode(['action' => 'unlike']);
} else {
    // User has not liked, add the like
    $insertSQL = $conn->prepare("INSERT INTO likes_table (user_id, post_id) VALUES (?, ?)");
    $insertSQL->bind_param("ii", $userID, $postID);
    $insertSQL->execute();
    echo json_encode(['action' => 'like']);
}

// Close the database connection
$conn->close();
?>