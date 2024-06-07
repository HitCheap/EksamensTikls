<?php
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Pieslegsanas/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Get the username of the logged-in user
$stmt = $conn->prepare("SELECT lietotājvārds FROM lietotaji WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found.";
    exit();
}

$username = $user['lietotājvārds'];

// Generate a CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        if (isset($_POST['add_note'])) {
            $noteText = trim($_POST['note_text']);
            if (!empty($noteText)) {
                $stmt = $conn->prepare("INSERT INTO user_notes (user_id, note_text) VALUES (?, ?)");
                $stmt->bind_param("is", $userId, $noteText);
                $stmt->execute();
                $stmt->close();
            }
        } elseif (isset($_POST['update_note'])) {
            $noteId = $_POST['note_id'];
            $noteText = trim($_POST['note_text']);
            if (!empty($noteText)) {
                $stmt = $conn->prepare("UPDATE user_notes SET note_text = ? WHERE id = ? AND user_id = ?");
                $stmt->bind_param("sii", $noteText, $noteId, $userId);
                $stmt->execute();
                $stmt->close();
            }
        } elseif (isset($_POST['delete_note'])) {
            $noteId = $_POST['note_id'];
            $stmt = $conn->prepare("DELETE FROM user_notes WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $noteId, $userId);
            $stmt->execute();
            $stmt->close();
        }
    }
    // Regenerate CSRF token after each form submission to prevent re-submission on refresh
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    header("Location: notepad.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM user_notes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Notepad</title>
    <link rel="stylesheet" href="notepad.css">
</head>
<body>
    <h2>Your Notepad</h2>
    <form action="notepad.php" method="POST">
        <textarea name="note_text" rows="4" cols="50" placeholder="Write your note here..."></textarea>
        <button type="submit" name="add_note">Add Note</button>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <a href="profile.php?username=<?php echo htmlspecialchars($username); ?>">Atpakaļ</a>
    </form>
    <h3>Your Notes</h3>
    <?php if (count($notes) > 0): ?>
        <ul>
            <?php foreach ($notes as $note): ?>
                <li>
                    <form action="notepad.php" method="POST">
                        <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                        <textarea name="note_text" rows="4" cols="50"><?php echo htmlspecialchars($note['note_text']); ?></textarea>
                        <button type="submit" name="update_note">Update</button>
                        <button type="submit" name="delete_note">Delete</button>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    </form>
                    <small>Created at: <?php echo $note['created_at']; ?></small>
                    <?php if ($note['created_at'] != $note['updated_at']): ?>
                        <small>(Edited: <?php echo $note['updated_at']; ?>)</small>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>You have no notes.</p>
    <?php endif; ?>
</body>
</html>
