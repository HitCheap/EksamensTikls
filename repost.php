<?php
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['id'])) {
        echo json_encode(['error' => 'User not logged in']);
        exit();
    }

    // Get the user ID from the session
    $userId = $_SESSION['id'];

    // Get the post ID from the request (You need to adjust this according to your application)
    $postId = isset($_POST['post_id']) ? $_POST['post_id'] : null;

    // Check if the post ID is valid
    if (!$postId) {
        echo json_encode(['error' => 'Invalid post ID']);
        exit();
    }

    // You need to implement your repost logic here
    // For example, toggle the repost status in the database

    // Example response
    $isReposted = true; // Assuming the post is reposted
    echo json_encode(['success' => true, 'isReposted' => $isReposted]);
} else {
    // Handle invalid request method
    echo json_encode(['error' => 'Invalid request method']);
}
?>
