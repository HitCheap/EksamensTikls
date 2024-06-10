<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
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

include 'datubaze.php';

$userId = $_SESSION['id'];

// Retrieve the username of the user to be blocked/unblocked
$userSql = $conn->prepare("SELECT lietotājvārds FROM lietotaji WHERE id = ?");
$userSql->bind_param('i', $blockedUserId);
$userSql->execute();
$userResult = $userSql->get_result();
if ($userResult->num_rows === 0) {
    echo json_encode(['error' => 'User not found']);
    $userSql->close();
    $conn->close();
    exit();
}
$userRow = $userResult->fetch_assoc();
$blockedUsername = $userRow['lietotājvārds'];
$userSql->close();

// Check if the user is already blocked
$checkSql = $conn->prepare("SELECT * FROM blocked_users WHERE user_id = ? AND blocked_user_id = ?");
$checkSql->bind_param('ii', $userId, $blockedUserId);
$checkSql->execute();
$result = $checkSql->get_result();

if ($result->num_rows > 0) {
    // User is already blocked, so unblock them
    $deleteSql = $conn->prepare("DELETE FROM blocked_users WHERE user_id = ? AND blocked_user_id = ?");
    $deleteSql->bind_param('ii', $userId, $blockedUserId);

    if ($deleteSql->execute()) {
        echo json_encode(['success' => true, 'isBlocked' => false, 'username' => $blockedUsername]);
    } else {
        echo json_encode(['error' => 'Failed to unblock user']);
    }

    $deleteSql->close();
} else {
    // User is not blocked, so block them
    $insertSql = $conn->prepare("INSERT INTO blocked_users (user_id, blocked_user_id) VALUES (?, ?)");
    $insertSql->bind_param('ii', $userId, $blockedUserId);

    if ($insertSql->execute()) {
        echo json_encode(['success' => true, 'isBlocked' => true, 'username' => $blockedUsername]);
    } else {
        echo json_encode(['error' => 'Failed to block user']);
    }

    $insertSql->close();
}

$checkSql->close();
$conn->close();
?>
