<?php
// functions.php

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

function addNotification($conn, $userId, $type, $message) {
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, type, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $type, $message);
    $stmt->execute();
    $stmt->close();
}

// Other common functions can be added here as needed
?>
