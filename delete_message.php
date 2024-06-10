<?php
session_start();
include 'datubaze.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo 'User not logged in';
    exit();
}

$currentUserId = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message_id'])) {
    $messageId = $_POST['message_id'];

    // Delete the message if it belongs to the current user
    $sql = "DELETE FROM messages WHERE id = ? AND sender_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $messageId, $currentUserId);

    if ($stmt->execute()) {
        echo 'Message deleted successfully';
    } else {
        echo 'Error deleting message';
    }

    $stmt->close();
}
?>
