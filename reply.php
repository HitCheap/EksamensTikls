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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $comment_id = $_POST['comment_id'];
    $reply_text = $_POST['reply_text'];
    $user_id = $_SESSION['id']; // Assuming you have the user ID stored in session after login

    $sql = $conn->prepare("INSERT INTO replies (comment_id, user_id, reply_text) VALUES (?, ?, ?)");
    $sql->bind_param("iis", $comment_id, $user_id, $reply_text);

    if ($sql->execute()) {
        header("Location: index.php"); // Redirect to the page with comments
        exit();
    } else {
        $_SESSION['error'] = "Failed to post reply. Please try again.";
        header("Location: index.php");
        exit();
    }
}
?>
