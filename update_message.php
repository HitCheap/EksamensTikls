<?php
session_start();
include 'datubaze.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo 'User not logged in';
    exit();
}

$currentUserId = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message_id']) && isset($_POST['content'])) {
    $messageId = $_POST['message_id'];
    $newContent = $_POST['content'];

    // Update the message content if the message belongs to the current user
    $sql = "UPDATE messages SET content = ? WHERE id = ? AND sender_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $newContent, $messageId, $currentUserId);

    if ($stmt->execute()) {
        echo 'Message updated successfully';
    } else {
        echo 'Error updating message';
    }

    $stmt->close();
}
?>
