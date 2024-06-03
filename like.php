<?php
session_start();

require_once 'functions.php';

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Datubāzes pieslēgums neveiksmīgs: ' . $conn->connect_error);
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Lietotājs nav pierakstījies']);
    exit();
}

$userID = $_SESSION['id'];
$postID = $_POST['post_id'];

// Check if the user already liked the post
$sql = $conn->prepare("SELECT * FROM likes_table WHERE user_id = ? AND post_id = ?");
$sql->bind_param("ii", $userID, $postID);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    // User already liked, remove the like
    $deleteSQL = $conn->prepare("DELETE FROM likes_table WHERE user_id = ? AND post_id = ?");
    $deleteSQL->bind_param("ii", $userID, $postID);
    $deleteSQL->execute();
    echo json_encode(['action' => 'atcelt patīk']);
} else {
    // User has not liked, add the like
    $insertSQL = $conn->prepare("INSERT INTO likes_table (user_id, post_id) VALUES (?, ?)");
    $insertSQL->bind_param("ii", $userID, $postID);
    $insertSQL->execute();

    // Create notification
$commentSQL = $conn->prepare("SELECT lietotaja_id FROM komentari WHERE comment_id = ?");
$commentSQL->bind_param("i", $postID);
$commentSQL->execute();
$commentResult = $commentSQL->get_result();
$commentData = $commentResult->fetch_assoc();

$notificationUserID = $commentData['lietotaja_id'];
if ($notificationUserID != $userID) {
    $message = "Your comment was liked by user ID: $userID";
    addNotification($conn, $notificationUserID, 'like', $message); // Use the addNotification function
}

    echo json_encode(['action' => 'patīk']);
}

$conn->close();
?>
