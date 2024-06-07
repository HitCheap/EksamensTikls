<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
  }
  
  include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $commentId = $_POST['comment_id'];
    $replyText = $_POST['reply_text'];
    $userId = $_SESSION['id'];

    if (!empty($replyText)) {
        $stmt = $conn->prepare("INSERT INTO atbildes (komentara_id, lietotaja_id, teksts, datums) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param('iis', $commentId, $userId, $replyText);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false]);
    }
}

$conn->close();
?>