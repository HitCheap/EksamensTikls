<?php
// Include database connection code here
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

include 'functions.php';

// Check if the user is logged in and get their ID
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Pieslegsanas/login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch notifications for the logged-in user
$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
</head>
<body>
    <h2>Notifications</h2>
    <?php if (count($notifications) > 0): ?>
        <ul>
            <?php foreach ($notifications as $notification): ?>
                <li>
                    <p><?php echo htmlspecialchars($notification['message']); ?></p>
                    <small><?php echo date('g:i A l, F j, Y', strtotime($notification['created_at'])); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No notifications found.</p>
    <?php endif; ?>
</body>
</html>
