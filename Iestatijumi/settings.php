<?php
session_start();

if (!isset($_SESSION['id'])) {
  header('Location: ../Pieslegsanas/login.php');
  exit();
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

// Encryption/Decryption constants
define('ENCRYPTION_KEY', 'your_encryption_key');
define('IV', '1234567890123456'); // 16-byte IV

// Encrypt email function
function encrypt_email($plainEmail) {
    $encryptedEmail = openssl_encrypt($plainEmail, 'aes-256-cbc', ENCRYPTION_KEY, 0, IV);
    if ($encryptedEmail === false) {
        return "Encryption failed.";
    }
    return $encryptedEmail;
}

// Decrypt email function
function decrypt_email($encryptedEmail) {
    $decryptedEmail = openssl_decrypt($encryptedEmail, 'aes-256-cbc', ENCRYPTION_KEY, 0, IV);
    if ($decryptedEmail === false) {
        return "Decryption failed.";
    }
    return $decryptedEmail;
}

// Fetch current email
$userId = $_SESSION['id'];
$sql = "SELECT epasts FROM lietotaji WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$encryptedEmail = $row['epasts'];
if ($encryptedEmail) {
    $currentEmail = decrypt_email($encryptedEmail); // Decrypt the email
} else {
    $currentEmail = "No email found.";
}

// Change Password
if (isset($_POST['change_password'])) {
  $currentPassword = $_POST['current_password'];
  $newPassword = $_POST['new_password'];
  $confirmPassword = $_POST['confirm_password'];

  // Check if new password matches confirmation password
  if ($newPassword !== $confirmPassword) {
      echo "New password and confirmation password do not match.";
      exit();
  }

  // Check if new password is at least 8 characters long
  if (strlen($newPassword) < 8) {
      echo "New password must be at least 8 characters long.";
      exit();
  }

  // Validate current password and check if new password is different
  $sql = "SELECT parole FROM lietotaji WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  
  if (password_verify($currentPassword, $row['parole'])) {
      // Check if new password is different from the current password
      if (password_verify($newPassword, $row['parole'])) {
          echo "New password cannot be the same as the current password.";
          exit();
      }

      // Additional logic to check against previous passwords if needed

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
    $updateSql = "UPDATE lietotaji SET statuss = 'Deaktivizēts' WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("i", $userId);
    if ($updateStmt->execute()) {
        // Log out user
        session_destroy();
        header("Location: ../Pieslegsanas/login.php");
        exit();
    } else {
        echo "Error updating account statuss: " . $conn->error;
    }
}

// Change Email
if (isset($_POST['change_email'])) {
    $newEmail = $_POST['new_email'];

    // Encrypt the new email
    $encryptedEmail = encrypt_email($newEmail);

    // Update email
    $updateEmailSql = "UPDATE lietotaji SET epasts = ? WHERE id = ?";
    $updateEmailStmt = $conn->prepare($updateEmailSql);
    $updateEmailStmt->bind_param("si", $encryptedEmail, $userId);
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
<button class="button" onclick="atpakalIndex()">Atpakaļ</button>
<script>
    function atpakalIndex() {
      window.location.href = '../index.php';
    }
</script>

<h2>Mainīt Paroli</h2>
<form action="" method="POST">
    <label for="current_password">Pašreizējā Parole:</label>
    <input type="password" id="current_password" name="current_password" required><br>
    <label for="new_password">Jaunā Parole:</label>
    <input type="password" id="new_password" name="new_password" required><br>
    <label for="confirm_password">Apstiprināt Jauno Paroli:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br>
    <button type="submit" name="change_password">Mainīt Paroli</button>
</form>

<h2>Dzēst Kontu</h2>
<form action="" method="POST">
    <button type="submit" name="delete_account">Dzēst Kontu</button>
</form>

<h2>Mainīt E-pastu</h2>
<form action="" method="POST">
    <label for="current_email">Pašreizējais E-pasts:</label>
    <input type="email" id="current_email" name="current_email" value="<?php echo htmlspecialchars($currentEmail); ?>" readonly><br>
    <label for="new_email">Jaunais E-pasts:</label>
    <input type="email" id="new_email" name="new_email" required><br>
    <button type="submit" name="change_email">Mainīt E-pastu</button>
</form>

</body>
</html>
