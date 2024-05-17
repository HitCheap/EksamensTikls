<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['id'];
$followedId = $_POST['followed_id'];

$mysqli = new mysqli("localhost", "root", "", "majaslapa");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$query = $mysqli->prepare("SELECT * FROM follows WHERE follower_id = ? AND followed_id = ?");
$query->bind_param("ii", $userId, $followedId);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $query = $mysqli->prepare("DELETE FROM follows WHERE follower_id = ? AND followed_id = ?");
    $query->bind_param("ii", $userId, $followedId);
    $query->execute();
    $isFollowing = false;
} else {
    $query = $mysqli->prepare("INSERT INTO follows (follower_id, followed_id) VALUES (?, ?)");
    $query->bind_param("ii", $userId, $followedId);
    $query->execute();
    $isFollowing = true;
}

$mysqli->close();

echo json_encode(['success' => true, 'isFollowing' => $isFollowing]);
?>
