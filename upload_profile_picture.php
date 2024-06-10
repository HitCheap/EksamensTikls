<?php
session_start();
include 'datubaze.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_SESSION['id'];

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Sanitize file name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // Directory in which to save the uploaded file
        $uploadFileDir = './profile_pictures/';
        
        // Check if the directory exists, if not create it
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }
        
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Update the profile picture in the database
            $sql = $conn->prepare("UPDATE lietotaji SET profile_picture = ? WHERE id = ?");
            $sql->bind_param('si', $newFileName, $userID);
            if ($sql->execute()) {
                $_SESSION['profile_picture'] = $newFileName;
                header('Location: profile.php?username=' . urlencode($_SESSION['lietotājvārds']));
                exit();
            } else {
                echo "Error updating profile picture in the database.";
            }
        } else {
            echo "There was an error moving the uploaded file.";
        }
    } else {
        echo "There was an error uploading the file.";
    }
} else {
    echo "Invalid request method.";
}
?>
