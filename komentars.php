<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentText = isset($_POST['teksts']) ? $_POST['teksts'] : '';
    $userId = $_SESSION['id'];
    $uploadDir = 'uploads/';

    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo'];
        $uploadFile = $uploadDir . basename($photo['name']);
        $fileType = pathinfo($uploadFile, PATHINFO_EXTENSION);

        // Check file type
        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($photo['tmp_name'], $uploadFile)) {
                $stmt = $conn->prepare("INSERT INTO komentari (lietotaja_id, teksts, photo) VALUES (?, ?, ?)");
                $stmt->bind_param('iss', $userId, $commentText, $uploadFile);
                if ($stmt->execute()) {
                    echo "Comment posted successfully!";
                } else {
                    echo "Error posting comment: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type.";
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO komentari (lietotaja_id, teksts) VALUES (?, ?)");
        $stmt->bind_param('is', $userId, $commentText);
        if ($stmt->execute()) {
            echo "Comment posted successfully!";
        } else {
            echo "Error posting comment: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
header('Location: index.php');
?>
