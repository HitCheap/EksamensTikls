<?php
session_start();
include 'datubaze.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo 'User not logged in';
    exit();
}

// Check if the user is an administrator
$userId = $_SESSION['id'];
$sql = $conn->prepare("SELECT statuss FROM lietotaji WHERE id =?");
$sql->bind_param("i", $userId);
$sql->execute();
$result = $sql->get_result();
$user = $result->fetch_assoc();
$isAdmin = $user['statuss'] === 'Administrators';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['notification_ids']) && $isAdmin) {
    $notificationIds = $_POST['notification_ids'];
    
    // Convert the notification IDs into a comma-separated string
    $idsString = implode(',', array_map('intval', $notificationIds));

    // Delete notifications from the database
    $deleteSql = "DELETE FROM notifications WHERE id IN ($idsString)";
    if ($conn->query($deleteSql) === TRUE) {
        echo 'Notifications deleted successfully';
    } else {
        echo 'Error deleting notifications: ' . $conn->error;
    }
} else {
    echo 'Invalid request';
}
?>
