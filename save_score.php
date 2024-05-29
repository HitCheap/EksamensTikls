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
    $game = $_POST['game']; // Assuming 'game' is passed in the POST data

    // Prepare the statement based on the game
    switch ($game) {
        case 'cuska':
            $table = 'rezcuska';
            break;
        case 'merkaTreneris':
            $table = 'rezmerkatreneris';
            break;
        case 'trex':
            $table = 'reztrex';
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid game']);
            exit();
    }

    $stmt = $conn->prepare("INSERT INTO $table (user_id, score) VALUES (?, ?)");
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
