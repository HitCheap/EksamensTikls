<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['id'])) {
        echo "You need to log in to reply.";
        exit();
    }

    $parent_comment_id = isset($_POST['parent_comment_id']) ? (int)$_POST['parent_comment_id'] : null;
    $reply_text = isset($_POST['reply_text']) ? trim($_POST['reply_text']) : '';
    $user_id = $_SESSION['id'];

    if ($parent_comment_id !== null && !empty($reply_text)) {
        $sql = "INSERT INTO komentari (teksts, parent_comment_id, lietotaja_id, datums) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sii", $reply_text, $parent_comment_id, $user_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header('Location: index.php');
                exit();
            } else {
                echo "Failed to add reply.";
            }

            $stmt->close();
        } else {
            echo "Failed to prepare the statement.";
        }
    } else {
        echo "Reply text cannot be empty and parent comment ID must be provided.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
