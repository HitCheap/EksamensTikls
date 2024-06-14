<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

include 'datubaze.php'; // Include your database connection

$game = $_GET['game'] ?? ''; // Get the 'game' parameter from GET data, default to empty string if not set

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

// Query to retrieve leaderboard data
$query = "
    SELECT 
        lietotaji.lietotājvārds, 
        $table.score 
    FROM 
        $table 
    JOIN 
        lietotaji 
    ON 
        $table.user_id = lietotaji.id 
    ORDER BY 
        $table.score DESC 
    LIMIT 10
";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$leaderboard = [];
while ($row = $result->fetch_assoc()) {
    $leaderboard[] = [
        'lietotājvārds' => $row['lietotājvārds'],
        'score' => $row['score']
    ];
}

$stmt->close();
$conn->close();

// Return JSON response with leaderboard data
echo json_encode([
    'success' => true,
    'leaderboard' => $leaderboard
]);


?>