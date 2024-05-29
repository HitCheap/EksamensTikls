<?php
session_start();
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
    exit();
}

$currentUserId = $_SESSION['id'];

// Fetch all users for the new conversation form
$sql = "SELECT id, lietotājvārds FROM lietotaji WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $currentUserId);
$stmt->execute();
$usersResult = $stmt->get_result();

// Handle new conversation creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['start_conversation'])) {
    $selectedUserIds = $_POST['user_ids'];
    if (!empty($selectedUserIds)) {
        // Fetch usernames of selected users
        $placeholders = implode(',', array_fill(0, count($selectedUserIds), '?'));
        $types = str_repeat('i', count($selectedUserIds));
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
        $conversationName = "Conversation with " . implode(", ", $usernames);
        $stmt->bind_param("s", $conversationName);
        $stmt->execute();
        $conversationId = $stmt->insert_id;

        // Add current user and selected users to the conversation
        $stmt = $conn->prepare("INSERT INTO conversation_members (conversation_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $conversationId, $currentUserId);
        $stmt->execute();
        foreach ($selectedUserIds as $userId) {
            $stmt->bind_param("ii", $conversationId, $userId);
            $stmt->execute();
        }

        header("Location: messenger.php?conversation_id=$conversationId");
        exit();
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
</head>
<body>
    <h1>Conversations</h1>
    <ul>
        <?php while ($conversation = $conversations->fetch_assoc()): ?>
            <li>
                <a href="?conversation_id=<?php echo $conversation['id']; ?>">
                    <?php echo htmlspecialchars($conversation['name']); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Messages</h2>
    <ul>
        <?php if ($messages && $messages->num_rows > 0): ?>
            <?php while ($message = $messages->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($message['lietotājvārds'] . " "); ?>:</strong>
                    <?php echo htmlspecialchars($message['content']); ?>
                    <em><?php echo $message['created_at']; ?></em>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <li>No messages found.</li>
        <?php endif; ?>
    </ul>

    <?php if (isset($_GET['conversation_id'])): ?>
        <form method="POST" action="send_message.php">
            <input type="hidden" name="conversation_id" value="<?php echo $_GET['conversation_id']; ?>">
            <textarea name="content" required></textarea>
            <button type="submit">Send</button>
        </form>
    <?php endif; ?>

    <h2>Start a New Conversation</h2>
    <form method="POST" action="messenger.php">
        <label>Select users to start a conversation with:</label><br>
        <?php while ($user = $usersResult->fetch_assoc()): ?>
            <input type="checkbox" name="user_ids[]" value="<?php echo $user['id']; ?>">
            <?php echo htmlspecialchars($user['lietotājvārds'] . " "); ?><br>
        <?php endwhile; ?>
        <button type="submit" name="start_conversation">Start Conversation</button>
    </form>
    <button class="button" onclick="atpakalIndex()">Atpakaļ</button>
    <script>
        function atpakalIndex() {
    window.location.href = 'index.php';
}
    </script>
</body>
</html>
