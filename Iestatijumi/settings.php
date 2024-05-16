<?php
session_start();

if (!isset($_SESSION['id'])) {
  header('Location: ../Pieslegsanas/login.php');
}

// Include your database connection code here
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Change Password
if (isset($_POST['change_password'])) {
  $userId = $_SESSION['id'];
  $currentPassword = $_POST['current_password'];
  $newPassword = $_POST['new_password'];
  $confirmPassword = $_POST['confirm_password'];

  // Check if new password matches confirmation password
  if ($newPassword !== $confirmPassword) {
      echo "New password and confirmation password do not match.";
      exit();
  }

  // Validate current password
  $sql = "SELECT parole FROM lietotaji WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  if (password_verify($currentPassword, $row['parole'])) {
      // Update password
      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
      $updateSql = "UPDATE lietotaji SET parole = ? WHERE id = ?";
      $updateStmt = $conn->prepare($updateSql);
      $updateStmt->bind_param("si", $hashedPassword, $userId);
      if ($updateStmt->execute()) {
          // Password updated successfully, destroy session and redirect to login page
          session_destroy();
          header("Location: ../Pieslegsanas/login.php");
          exit();
      } else {
          echo "Error updating password: " . $conn->error;
      }
  } else {
      echo "Current password is incorrect.";
  }
}


// Delete Account
if (isset($_POST['delete_account'])) {
    $userId = $_SESSION['id'];
    $deleteSql = "DELETE FROM lietotaji WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $userId);
    if ($deleteStmt->execute()) {
        // Log out user
        session_destroy();
        header("Location: ../Pieslegsanas/login.php");
    } else {
        echo "Error deleting account: " . $conn->error;
    }
}

// Change Email
if (isset($_POST['change_email'])) {
    $userId = $_SESSION['id'];
    $newEmail = $_POST['new_email'];

    // Update email
    $updateEmailSql = "UPDATE lietotaji SET epasts = ? WHERE id = ?";
    $updateEmailStmt = $conn->prepare($updateEmailSql);
    $updateEmailStmt->bind_param("si", $newEmail, $userId);
    if ($updateEmailStmt->execute()) {
        echo "Email updated successfully.";
    } else {
        echo "Error updating email: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iestatījumi</title>
    <link rel="stylesheet" href="settings.css">
</head>
<body>
<button class="button" onclick="atpakalIndex()">Atpakal</button>
<script>
        function atpakalIndex() {
      window.location.href = '../index.php';
    }
    </script>
    <h2>Change Password</h2>
    <form action="" method="POST">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required><br>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        <button type="submit" name="change_password">Change Password</button>
    </form>

    <h2>Delete Account</h2>
    <form action="" method="POST">
        <button type="submit" name="delete_account">Delete Account</button>
    </form>

    <h2>Change Email</h2>
    <form action="" method="POST">
        <label for="new_email">New Email:</label>
        <input type="email" id="new_email" name="new_email" required><br>
        <button type="submit" name="change_email">Change Email</button>
    </form>
</body>
</html>
