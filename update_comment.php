<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Check if the user is logged in
    if (!isset($_SESSION['id'])) {
        echo json_encode(['error' => 'User not logged in']);
        exit();
    }

    // Get the comment ID and edited comment text from the POST data
    $commentId = $_POST['comment_id'];
    $editedCommentText = $_POST['comment_text'];

    // Update the comment text in the database
    $sql = $conn->prepare("UPDATE komentari SET teksts = ? WHERE comment_id = ?");
    $sql->bind_param("si", $editedCommentText, $commentId);

    if ($sql->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to update comment']);
    }
}
?>
