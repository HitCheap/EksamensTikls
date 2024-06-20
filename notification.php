<?php
session_start();
include 'datubaze.php';
include 'navbar.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user information
$userId = $_SESSION['id'];
$sql = $conn->prepare("SELECT statuss FROM lietotaji WHERE id =?");
$sql->bind_param("i", $userId);
$sql->execute();
$result = $sql->get_result();
$user = $result->fetch_assoc();

// Check if the user is an administrator
$isAdmin = $user['statuss'] === 'Administrators';

// Fetch notifications for the logged-in user
$notificationsSql = $conn->prepare("SELECT id, message, created_at FROM notifications WHERE user_id =? ORDER BY created_at DESC");
$notificationsSql->bind_param("i", $userId);
$notificationsSql->execute();
$notificationsResult = $notificationsSql->get_result();
$notifications = $notificationsResult->fetch_all(MYSQLI_ASSOC);

if (!$isAdmin) {
    // Delete notifications after they are viewed by a normal user
    $deleteNotificationsSql = $conn->prepare("DELETE FROM notifications WHERE user_id =?");
    $deleteNotificationsSql->bind_param("i", $userId);
    $deleteNotificationsSql->execute();
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paziņojumi</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Add a margin-top to the body to make room for the navbar */
        body {
            margin-top: 50px; /* adjust the value to match the height of your navbar */
        }
    </style>
</head>
<body>
    <h1>Paziņojumi</h1>

    <?php if ($isAdmin):?>
        <button onclick="exportToExcel()">Export to Excel</button>
        <button id="selectAll">Select All</button>
        <button id="deleteSelected">Delete Selected</button>
    <?php endif;?>

    <ul>
        <?php foreach ($notifications as $notification):?>
            <li>
                <?php if ($isAdmin):?>
                    <input type="checkbox" class="notification-checkbox" value="<?php echo $notification['id'];?>">
                <?php endif;?>
                <?php echo htmlspecialchars($notification['message']);?> - <?php echo $notification['created_at'];?>
            </li>
        <?php endforeach;?>
    </ul>

    <script>
        function exportToExcel() {
    var notifications = <?php echo json_encode($notifications);?>;
    var ws = XLSX.utils.json_to_sheet(notifications.map(notification => ({
        'Message': notification.message,
        'Created At': notification['created_at']
    })));
    ws['!cols'] = [
        {wch: 40}, // Set the width of the first column (A) to 400 pixels
        {wch: 20}  // Set the width of the second column (B) to 200 pixels
    ];
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Notifications');
    XLSX.writeFile(wb, 'notifications.xlsx');
}

        $(document).ready(function() {
            $('#selectAll').click(function() {
                var isChecked = this.checked;
                $('.notification-checkbox').each(function() {
                    this.checked = isChecked;
                });
            });

            $('.notification-checkbox').change(function() {
                if (!this.checked) {
                    $('#selectAll').prop('checked', false);
                } else if ($('.notification-checkbox:checked').length === $('.notification-checkbox').length) {
                    $('#selectAll').prop('checked', true);
                }
            });

            $('#deleteSelected').click(function() {
                var selectedNotifications = $('.notification-checkbox:checked').map(function() {
                    return this.value;
                }).get();

                if (selectedNotifications.length > 0) {
                    $.ajax({
                        url: 'delete_notifications.php',
                        method: 'POST',
                        data: { notification_ids: selectedNotifications },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
