<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['found_easter_egg']) && $_SESSION['found_easter_egg']) {
    // Update the database to add a star to the username
    $userId = $_SESSION['user_id'];
    
    $conn = new mysqli('localhost', 'root', '', 'majaslapa');
    if ($conn->connect_error) {
        die('Database connection failed: ' . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("UPDATE lietotaji SET lietotājvārds = CONCAT(lietotājvārds, '*') WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    $_SESSION['found_easter_egg'] = false; // Reset the session variable
    
    header("Location: profile.php?username=" . urlencode($username) . "&easter_egg=found");
    exit();
}
?>
