<?php
session_start();
include 'datubaze.php';

// Encryption/Decryption constants
define('ENCRYPTION_KEY', 'your_encryption_key'); // Replace with your actual key
define('IV', '1234567890123456'); // 16-byte IV

function encrypt_email($plainEmail) {
    return openssl_encrypt($plainEmail, 'aes-256-cbc', ENCRYPTION_KEY, 0, IV);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $epasts = strtolower(trim($_POST['epasts']));  // Normalize email to lowercase
    $encryptedEmail = encrypt_email($epasts); // Encrypt the email

    $sql = $conn->prepare("SELECT * FROM lietotaji WHERE epasts = ?");
    $sql->bind_param("s", $encryptedEmail);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Insert token into the database
        $sql = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $encryptedEmail, $token, $expiry);
        $sql->execute();

        // Display reset link directly
        $resetLink = "http://localhost/reset_password.php?token=$token";
        $_SESSION['message'] = "Paroles atjaunošanas saite: <a href='$resetLink'>$resetLink</a>";
    } else {
        $_SESSION['error'] = "Šis epasts nav reģistrēts.";
    }
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <link rel="stylesheet" href="pieslegsanas.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paroles atjaunošana</title>
</head>
<body class="mx-2">
  <main class="login-container">
    <hr>
    <form class="login-form" action="password_reset.php" method="POST">
      <p class="login-title">Atjaunot paroli</p>
      <div class="input-group">
        <label for="epasts" class="input-label">E-pasts:</label>
        <input type="email" id="epasts" name="epasts" placeholder="epasts@gmail.com" class="input-field" required/>
      </div>
      <div class="button-group">
        <button class="login-button" type="submit">Sūtīt atjaunošanas saiti</button>
        <button class="back-button" type="button" onclick="history.back()">Atpakaļ</button>
        <?php
          if (isset($_SESSION['error'])) {
              echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
              unset($_SESSION['error']);
          }
          if (isset($_SESSION['message'])) {
              echo '<div class="success-message">' . $_SESSION['message'] . '</div>';
              unset($_SESSION['message']);
          }
        ?>
      </div>
    </form>
  </main>
</body>
</html>
