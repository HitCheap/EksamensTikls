<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
  }
  
  include 'datubaze.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conversationId = $_POST['conversation_id'];
    $content = $_POST['content'];
    $senderId = $_SESSION['id'];

    $stmt = $conn->prepare("INSERT INTO messages (conversation_id, sender_id, content, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $conversationId, $senderId, $content);
    $stmt->execute();

    header("Location: messenger.php?conversation_id=$conversationId");
    exit();
}
?>
