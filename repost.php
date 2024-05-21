<?php
session_start();
header('Content-Type: application/json');

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Database connection failed.']));
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'You must be logged in to repost.']);
    exit();
}

$userId = $_SESSION['id'];
$contentId = $_POST['content_id'];

// Check if the user has already reposted this content
$sql = "SELECT * FROM reposts WHERE user_id = ? AND content_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $contentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User has already reposted, so we will remove the repost
    $sql = "DELETE FROM reposts WHERE user_id = ? AND content_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $contentId);
    $stmt->execute();
    echo json_encode(['success' => true, 'isReposted' => false]);
} else {
    // User has not reposted yet, so we will add a new repost
    $sql = "INSERT INTO reposts (user_id, content_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $contentId);
    $stmt->execute();
    echo json_encode(['success' => true, 'isReposted' => true]);
}
?>
