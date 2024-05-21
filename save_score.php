<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'majaslapa';
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit();
    }

    $userId = $_SESSION['id'];
    $score = intval($_POST['score']);

    $stmt = $conn->prepare("INSERT INTO scores (user_id, score) VALUES (?, ?)");
    $stmt->bind_param('ii', $userId, $score);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Score saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving score']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
