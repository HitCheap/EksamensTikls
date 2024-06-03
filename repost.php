<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];
$content_id = $_POST['content_id'];

// Check if the user has already reposted this content
$sql = "SELECT * FROM reposts WHERE user_id = ? AND content_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $user_id, $content_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User has already reposted this content, so delete the repost
    $sql = "DELETE FROM reposts WHERE user_id = ? AND content_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $content_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'isReposted' => false, 'message' => 'Repost removed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove repost']);
    }
} else {
    // User has not reposted this content, so insert a new repost
    $sql = "INSERT INTO reposts (user_id, content_id, repost_date) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $content_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'isReposted' => true, 'message' => 'Reposted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to repost']);
    }
}

$stmt->close();
$conn->close();
?>
