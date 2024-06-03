<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
  }
  
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

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
