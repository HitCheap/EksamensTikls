<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Your database connection and follow/unfollow logic here

// Example response
echo json_encode(['success' => true, 'isFollowing' => $isFollowing]); // $isFollowing should be a boolean indicating current follow status
?>
