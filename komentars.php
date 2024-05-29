<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['id'];
    $teksts = $conn->real_escape_string($_POST['teksts']);
    $media = null;
    $parent_comment_id = isset($_POST['parent_comment_id']) ? intval($_POST['parent_comment_id']) : NULL;

    // Ensure the uploads directory exists and is writable
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Handle file upload
    if (isset($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['media']['tmp_name'];
        $fileName = $_FILES['media']['name'];
        $fileSize = $_FILES['media']['size'];
        $fileType = $_FILES['media']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Sanitize file name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // Directory to save the file
        $dest_path = $targetDir . $newFileName;

        // Check file size (50MB maximum)
        if ($fileSize > 50000000) {
            echo "Sorry, your file is too large.";
            exit;
        }

        // Allow certain file formats
        $allowedFileTypes = ["jpg", "jpeg", "png", "gif", "mp4", "avi", "mov", "wmv", "mp3"];
        if (!in_array($fileExtension, $allowedFileTypes)) {
            echo "Sorry, only JPG, JPEG, PNG, GIF, MP4, AVI, MOV, WMV, and MP3 files are allowed.";
            exit;
        }

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $media = $dest_path;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO komentari (teksts, lietotaja_id, media, datums) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param('sis', $teksts, $user_id, $media);

    if ($stmt->execute()) {
        header('Location: index.php'); // Redirect to a success page
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
