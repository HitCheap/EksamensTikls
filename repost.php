<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];
$content_id = $_POST['content_id'];

// Check if the user has already reposted this content
$sql = "SELECT * FROM reposts WHERE user_id = ? AND content_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $user_id, $content_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User has already reposted this content, so delete the repost
    $sql = "DELETE FROM reposts WHERE user_id = ? AND content_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $content_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'isReposted' => false, 'message' => 'Repost removed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove repost']);
    }
} else {
    // User has not reposted this content, so insert a new repost
    $sql = "INSERT INTO reposts (user_id, content_id, repost_date) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $content_id);

    if ($stmt->execute()) {
        // Fetch the original content details
        $contentSql = "SELECT k.teksts AS content, u.lietotājvārds AS original_user FROM komentari k JOIN lietotaji u ON k.lietotaja_id = u.id WHERE k.comment_id = ?";
        $contentStmt = $conn->prepare($contentSql);
        $contentStmt->bind_param('i', $content_id);
        $contentStmt->execute();
        $contentResult = $contentStmt->get_result();
        $contentData = $contentResult->fetch_assoc();

        // Fetch the current user's username
        $userSql = "SELECT lietotājvārds FROM lietotaji WHERE id = ?";
        $userStmt = $conn->prepare($userSql);
        $userStmt->bind_param('i', $user_id);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        $userData = $userResult->fetch_assoc();

        echo json_encode([
            'success' => true, 
            'isReposted' => true, 
            'message' => 'Reposted successfully', 
            'content' => $contentData['content'],
            'original_user' => $contentData['original_user'],
            'current_user' => $userData['lietotājvārds'],
            'repost_date' => date('Y-m-d H:i:s')
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to repost']);
    }
}

$stmt->close();
$conn->close();
?>
