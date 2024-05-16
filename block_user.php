<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
    exit();
}

// Check if the blocked_user_id is provided and valid
if (!isset($_POST['blocked_user_id'])) {
    echo json_encode(['error' => 'Blocked user ID not provided']);
    exit();
}

$blockedUserId = $_POST['blocked_user_id'];

// Check if the user is trying to block themselves
if ($_SESSION['id'] == $blockedUserId) {
    echo json_encode(['error' => 'Cannot block yourself']);
    exit();
}

// Establish database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Check if the user is already blocked
$userId = $_SESSION['id'];
$checkSql = $conn->prepare("SELECT * FROM blocked_users WHERE user_id = ? AND blocked_user_id = ?");
$checkSql->bind_param('ii', $userId, $blockedUserId);
$checkSql->execute();
$result = $checkSql->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['error' => 'User already blocked']);
    exit();
}

// Insert the block record into the database
$insertSql = $conn->prepare("INSERT INTO blocked_users (user_id, blocked_user_id) VALUES (?, ?)");
$insertSql->bind_param('ii', $userId, $blockedUserId);

if ($insertSql->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to block user']);
}

$insertSql->close();
$checkSql->close();
$conn->close();
?>
