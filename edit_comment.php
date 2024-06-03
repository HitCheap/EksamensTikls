<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$comment_id = $_POST['comment_id'];
$new_text = $_POST['new_text'];
$user_id = $_SESSION['id'];

$sql = "UPDATE komentari SET teksts = ?, is_edited = 1 WHERE comment_id = ? AND lietotaja_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sii', $new_text, $comment_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Comment edited successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to edit comment']);
}

$stmt->close();
$conn->close();
?>
