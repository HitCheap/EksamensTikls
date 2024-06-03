<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Datubāzes pieslēgums neveiksmīgs: ' . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
    exit();
}

// Fetch user information
$userId = $_SESSION['id'];
$sql = $conn->prepare("SELECT statuss FROM lietotaji WHERE id = ?");
$sql->bind_param("i", $userId);
$sql->execute();
$result = $sql->get_result();
$user = $result->fetch_assoc();

// Check if the user is an administrator
$isAdmin = $user['statuss'] === 'Administrators';

// Fetch notifications for the logged-in user
$notificationsSql = $conn->prepare("SELECT message, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$notificationsSql->bind_param("i", $userId);
$notificationsSql->execute();
$notificationsResult = $notificationsSql->get_result();
$notifications = $notificationsResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
</head>
<body>
    <h1>Notifications</h1>

    <?php if ($isAdmin): ?>
    <button onclick="exportToExcel()">Export to Excel</button>
<?php endif; ?>

<ul>
    <?php foreach ($notifications as $notification): ?>
        <li><?php echo htmlspecialchars($notification['message']); ?> - <?php echo $notification['created_at']; ?></li>
    <?php endforeach; ?>
</ul>

<script>
    function exportToExcel() {
        var notifications = <?php echo json_encode($notifications); ?>;
        var ws = XLSX.utils.json_to_sheet(notifications.map(notification => ({
            'Message': notification.message,
            'Created At': notification['created_at']
        })));
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Notifications');
        XLSX.writeFile(wb, 'notifications.xlsx');
    }
</script>
</body>
</html>
