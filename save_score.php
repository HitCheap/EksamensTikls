<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'datubaze.php';

    $userId = $_SESSION['id'];
    $score = intval($_POST['score']);
    $game = $_POST['game']; // Assuming 'game' is passed in the POST data

    // Prepare the statement based on the game
    switch ($game) {
        case 'cuska':
            $table = 'rezcuska';
            break;
        case 'tetris':
            $table = 'reztet';
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

    // Check if the score is valid
    if ($score <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid score']);
        exit();
    }

    // Check the current best score for the user in the game
    $stmt = $conn->prepare("SELECT id, score FROM $table WHERE user_id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($id, $currentBestScore);
    $stmt->fetch();
    $stmt->close();

    if ($currentBestScore !== null) {
        // If the new score is better than the current best score, update it
        if ($score > $currentBestScore) {
            $stmt = $conn->prepare("UPDATE $table SET score = ? WHERE id = ?");
            $stmt->bind_param('ii', $score, $id);
        } else {
            echo json_encode(['success' => false, 'message' => 'New score is not better than the current best score']);
            $conn->close();
            exit();
        }
    } else {
        // If there is no current score, insert the new score
        $stmt = $conn->prepare("INSERT INTO $table (user_id, score) VALUES (?, ?)");
        $stmt->bind_param('ii', $userId, $score);
    }

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
