<?php
session_start();
include 'datubaze.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Lietotājs nav pierakstījies']);
    exit();
}

$userID = $_SESSION['id'];

// Fetch notifications for the logged-in user
$sql = $conn->prepare("SELECT message FROM notifications WHERE user_id = ?");
$sql->bind_param("i", $userID);
$sql->execute();
$result = $sql->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode($notifications);

$conn->close();
?>
