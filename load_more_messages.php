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

if (isset($_GET['profile_id']) && isset($_GET['offset']) && isset($_GET['limit'])) {
    $profileId = (int)$_GET['profile_id'];
    $offset = (int)$_GET['offset'];
    $limit = (int)$_GET['limit'];

    $commentsSql = $conn->prepare("SELECT teksts, datums FROM komentari WHERE lietotaja_id = ? ORDER BY datums DESC LIMIT ?, ?");
    $commentsSql->bind_param('iii', $profileId, $offset, $limit);
    $commentsSql->execute();
    $commentsResult = $commentsSql->get_result();

    $messages = [];
    while ($comment = $commentsResult->fetch_assoc()) {
        $messages[] = $comment;
    }

    $hasMore = $commentsResult->num_rows === $limit;

    echo json_encode(['messages' => $messages, 'hasMore' => $hasMore]);
}
?>
