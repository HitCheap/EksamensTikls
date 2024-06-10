<?php
session_start();
include 'datubaze.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$currentUserId = $_SESSION['id'];

// Fetch all users for the new conversation form with mutual following check
$sql = "SELECT id, lietotājvārds 
        FROM lietotaji 
        WHERE id != ? AND id IN (
            SELECT followed_id 
            FROM follows 
            WHERE follower_id = ? 
            AND followed_id IN (
                SELECT follower_id 
                FROM follows 
                WHERE followed_id = ?
            )
        )";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $currentUserId, $currentUserId, $currentUserId);
$stmt->execute();
$usersResult = $stmt->get_result();

// Handle new conversation creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['start_conversation'])) {
    $selectedUserIds = $_POST['user_ids'];
    if (!empty($selectedUserIds)) {
        // Check if all selected users are mutually following
        $placeholders = implode(',', array_fill(0, count($selectedUserIds), '?'));
        $types = str_repeat('i', count($selectedUserIds));

        $mutualCheckSql = "SELECT COUNT(*) AS count 
                           FROM follows f1 
                           JOIN follows f2 ON f1.followed_id = f2.follower_id 
                           WHERE f1.follower_id = ? AND f1.followed_id IN ($placeholders)";
        $stmt = $conn->prepare($mutualCheckSql);
        $stmt->bind_param("i" . $types, $currentUserId, ...$selectedUserIds);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        // Debugging: Check the mutual following count
        error_log("Mutual following count: " . $result['count'] . " Expected: " . count($selectedUserIds));

        if ($result['count'] == count($selectedUserIds)) {
            // Fetch usernames of selected users
            $stmt = $conn->prepare("SELECT lietotājvārds FROM lietotaji WHERE id IN ($placeholders)");
            $stmt->bind_param($types, ...$selectedUserIds);
            $stmt->execute();
            $usernamesResult = $stmt->get_result();

            $usernames = [];
            while ($row = $usernamesResult->fetch_assoc()) {
                $usernames[] = $row['lietotājvārds'];
            }

            // Create a new conversation
            $stmt = $conn->prepare("INSERT INTO conversations (name) VALUES (?)");
            $conversationName = "Saruna ar " . implode(", ", $usernames);
            $stmt->bind_param("s", $conversationName);
            if ($stmt->execute()) {
                $conversationId = $stmt->insert_id;
                error_log("New conversation created with ID: " . $conversationId);

                // Add current user and selected users to the conversation
                $stmt = $conn->prepare("INSERT INTO conversation_members (conversation_id, user_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $conversationId, $currentUserId);
                if ($stmt->execute()) {
                    error_log("Current user added to conversation.");
                } else {
                    error_log("Error adding current user to conversation.");
                }
                foreach ($selectedUserIds as $userId) {
                    $stmt->bind_param("ii", $conversationId, $userId);
                    if ($stmt->execute()) {
                        error_log("User ID $userId added to conversation.");
                    } else {
                        error_log("Error adding user ID $userId to conversation.");
                    }
                }

                header("Location: messenger.php?conversation_id=$conversationId");
                exit();
            } else {
                error_log("Error creating new conversation.");
            }
        } else {
            error_log("One or more selected users are not mutually following you.");
            echo "One or more selected users are not mutually following you.";
        }
    }
}

// Fetch conversations
$sql = "SELECT c.id, c.name 
        FROM conversations c
        JOIN conversation_members cm ON c.id = cm.conversation_id
        WHERE cm.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $currentUserId);
$stmt->execute();
$conversations = $stmt->get_result();

// Fetch messages for a selected conversation
$messages = [];
if (isset($_GET['conversation_id'])) {
    $conversationId = $_GET['conversation_id'];
    $sql = "SELECT m.*, u.lietotājvārds 
            FROM messages m
            JOIN lietotaji u ON m.sender_id = u.id
            WHERE m.conversation_id = ?
            ORDER BY m.created_at ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $conversationId);
    $stmt->execute();
    $messages = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messenger</title>
    <link rel="stylesheet" href="messenger.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="messenger-container">
        <div class="conversations-sidebar">
            <h2>Sarunas</h2>
            <ul>
                <?php while ($conversation = $conversations->fetch_assoc()):?>
                    <li>
                        <a href="?conversation_id=<?php echo $conversation['id'];?>">
                            <?php echo htmlspecialchars($conversation['name']);?>
                        </a>
                    </li>
                <?php endwhile;?>
            </ul>
        </div>
        <div class="messages-container">
            <div class="messages-header">
                <h2>Ziņas</h2>
            </div>
            <ul class="messages-list">
                <?php if ($messages && $messages->num_rows > 0):?>
                    <?php while ($message = $messages->fetch_assoc()):?>
                        <li data-message-id="<?php echo $message['id'];?>">
                            <div class="message-avatar">
                                <?php
                                    $usernameParts = explode(' ', $message['lietotājvārds']);
                                    $initials = '';
                                    foreach ($usernameParts as $part) {
                                        $initials .= substr($part, 0, 1);
                                    }
                                    echo $initials;
                                ?>
                            </div>
                            <div class="message-content">
                                <?php echo htmlspecialchars($message['content']);?>
                                <em><?php echo $message['created_at'];?></em>
                                <?php if ($message['sender_id'] == $currentUserId): ?>
                                    <button class="edit-button">Edit</button>
                                    <button class="delete-button">Delete</button>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endwhile;?>
                <?php else:?>
                    <li>No atrastas ziņas.</li>
                <?php endif;?>
            </ul>
            <?php if (isset($_GET['conversation_id'])):?>
                <form method="POST" action="send_message.php">
                    <input type="hidden" name="conversation_id" value="<?php echo $_GET['conversation_id'];?>">
                    <textarea name="content" required></textarea>
                    <button type="submit">Send</button>
                </form>
            <?php endif;?>
        </div>
        <div class="new-conversation-sidebar">
            <h2>Sākt jaunu sarunu</h2>
            <form method="POST" action="messenger.php" onsubmit="return validateForm()">
                <label>Izvēlies lietotājus ar kuriem vēlies sākt sarunu:</label><br>
                <?php while ($user = $usersResult->fetch_assoc()):?>
                    <input type="checkbox" name="user_ids[]" value="<?php echo $user['id'];?>">
                    <?php echo htmlspecialchars($user['lietotājvārds'] . " ");?><br>
                <?php endwhile;?>
                <button type="submit" id="startConversationBtn" name="start_conversation" disabled>Sākt sarunu</button>
            </form>
            <button class="button" onclick="atpakalIndex()">Atpakaļ</button>
        </div>
    </div>

    <script>
        function atpakalIndex() {
            window.location.href = 'index.php';
        }

        function validateForm() {
            const checkboxes = document.querySelectorAll('input[name="user_ids[]"]:checked');
            return checkboxes.length > 0;
        }

        const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
        const startConversationBtn = document.getElementById('startConversationBtn');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const checkedCount = document.querySelectorAll('input[name="user_ids[]"]:checked').length;
                startConversationBtn.disabled = checkedCount === 0;
            });
        });

        // Edit message functionality
        $('.edit-button').on('click', function() {
            var messageId = $(this).closest('li').data('message-id');
            var messageContent = $(this).closest('li').find('.message-content').text();
            var editInput = $('<input type="text" value="' + messageContent + '">');
            $(this).closest('li').find('.message-content').html(editInput);
            editInput.focus();
        });

        // Save edited message
        $('.messages-list').on('blur', 'input', function() {
            var messageId = $(this).closest('li').data('message-id');
            var newMessageContent = $(this).val();
            // Send AJAX request to update message content in database
            $.ajax({
                type: 'POST',
                url: 'update_message.php',
                data: { message_id: messageId, content: newMessageContent },
                success: function() {
                    // Refresh message list
                    location.reload();
                }
            });
        });

        // Delete message functionality
        $('.delete-button').on('click', function() {
            var messageId = $(this).closest('li').data('message-id');
            if (confirm('Are you sure you want to delete this message?')) {
                // Send AJAX request to delete message from database
                $.ajax({
                    type: 'POST',
                    url: 'delete_message.php',
                    data: { message_id: messageId },
                    success: function() {
                        // Refresh message list
                        location.reload();
                    }
                });
            }
        });
    </script>
</body>
</html>