<?php
// upload_profile_picture.php

session_start();
$userId = $_SESSION['id'];

if (!isset($_SESSION['id'])) {
  header('Location: Pieslegsanas/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $uploadDir = 'bildes/';
    $uploadFile = $uploadDir . basename($_FILES['profile_picture']['name']);
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES['profile_picture']['tmp_name']);

    // Check if the file is an image
    if ($check !== false) {
        // Check file size (5MB max)
        if ($_FILES['profile_picture']['size'] <= 5000000) {
            // Allow only certain file formats
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                // Move the uploaded file to the designated directory
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
                    // Update the user's profile picture in the database
                    $sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('si', $uploadFile, $userId);

                    if ($stmt->execute()) {
                        echo json_encode(['success' => true, 'message' => 'Profile picture updated successfully.']);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Database update failed: ' . $stmt->error]);
                    }
                } else {
                    echo json_encode(['success' => false, 'error' => 'File upload failed.']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Only JPG, JPEG, PNG & GIF files are allowed.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'File is too large.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'File is not an image.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
?>
