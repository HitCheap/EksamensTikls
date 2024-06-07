<?php
session_start();
require_once 'functions.php';
include 'database.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Lietotājs nav pierakstījies']);
    exit();
}

if (!isset($_POST['post_id'])) {
    echo json_encode(['error' => 'post_id is not set']);
    exit();
}

$userID = $_SESSION['id'];
$postID = $_POST['post_id'];

// Check if the user is an administrator
$userRoleSQL = $conn->prepare("SELECT statuss FROM lietotaji WHERE id = ?");
$userRoleSQL->bind_param("i", $userID);
$userRoleSQL->execute();
$userRoleResult = $userRoleSQL->get_result();
$userRole = $userRoleResult->fetch_assoc()['statuss'];
$isAdministrator = ($userRole === 'Administrators');

if (empty($postID)) {
    echo json_encode(['error' => 'post_id is empty']);
    exit();
}

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
    $action = 'atcelt patīk';
} else {
    // User has not liked, add the like
    $insertSQL = $conn->prepare("INSERT INTO likes_table (user_id, post_id) VALUES (?, ?)");
    $insertSQL->bind_param("ii", $userID, $postID);
    $insertSQL->execute();
    $action = 'patīk';

    // Create notification
    $commentSQL = $conn->prepare("SELECT lietotaja_id, teksts FROM komentari WHERE comment_id = ?");
    $commentSQL->bind_param("i", $postID);
    $commentSQL->execute();
    $commentResult = $commentSQL->get_result();
    $commentData = $commentResult->fetch_assoc();

    $notificationUserID = $commentData['lietotaja_id'];
    $commentText = $commentData['teksts'];
    $likerNameSQL = $conn->prepare("SELECT lietotājvārds FROM lietotaji WHERE id = ?");
    $likerNameSQL->bind_param("i", $userID);
    $likerNameSQL->execute();
    $likerName = $likerNameSQL->get_result()->fetch_assoc()['lietotājvārds'];

    if ($notificationUserID != $userID) {
        $message = "Uz tavu komentāru tika nospiests patīk";
        addNotification($conn, $notificationUserID, 'like', $message);
    }

    if ($isAdministrator) {
        $message = "Comment '$commentText' was liked by $likerName";
        addNotification($conn, $userID, 'like', $message); // Notify the admin
    }
}

// Fetch the updated like count
$likeCountSQL = $conn->prepare("SELECT COUNT(*) AS like_count FROM likes_table WHERE post_id = ?");
$likeCountSQL->bind_param("i", $postID);
$likeCountSQL->execute();
$likeCountResult = $likeCountSQL->get_result();
$likeCount = $likeCountResult->fetch_assoc()['like_count'];

$response = ['action' => $action, 'like_count' => $likeCount];
echo json_encode($response);

$conn->close();
?>
