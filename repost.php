<?php
session_start();
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['id'];
$contentId = $_POST['content_id'];

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the repost already exists
$sql = "SELECT * FROM reposts WHERE user_id = ? AND content_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $userId, $contentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Repost exists, so remove it
    $sql = "DELETE FROM reposts WHERE user_id = ? AND content_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $userId, $contentId);
    $stmt->execute();
    echo json_encode(['success' => true, 'isReposted' => false]);
} else {
    // Repost does not exist, so add it
    $sql = "INSERT INTO reposts (user_id, content_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $userId, $contentId);
    $stmt->execute();
    echo json_encode(['success' => true, 'isReposted' => true]);
}

$conn->close();
?>
