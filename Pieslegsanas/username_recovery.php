<?php
// Include your database connection and encryption/decryption code here
include '../database.php';

// Encryption/Decryption constants
define('ENCRYPTION_KEY', 'your_encryption_key'); // Replace with your actual key
define('IV', '1234567890123456'); // 16-byte IV

function encrypt_email($plainEmail) {
    $encryptedEmail = openssl_encrypt($plainEmail, 'aes-256-cbc', ENCRYPTION_KEY, 0, IV);
    if ($encryptedEmail === false) {
        return "Encryption failed.";
    }
    return $encryptedEmail;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $encryptedEmail = encrypt_email($email); // Encrypt the email before querying the database

    $sql = $conn->prepare("SELECT lietotājvārds FROM lietotaji WHERE epasts = ?");
    $sql->bind_param("s", $encryptedEmail);
    $sql->execute();
    $sql->bind_result($lietotājvārds);

    if ($sql->fetch()) {
        // Send the username to the user's email
        $to = $email;
        $subject = "Your Username Recovery";
        $message = "Hello,\n\nYour username is: " . htmlspecialchars($lietotājvārds) . "\n\nBest regards,\nYour Website Team";
        $headers = "From: no-reply@yourwebsite.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "An email with your username has been sent to your email address.";
        } else {
            echo "Failed to send email.";
        }
    } else {
        echo "Email not found.";
    }
    $sql->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Username</title>
    <link rel="stylesheet" href="atgut.css">
</head>
<body>
    <h1>Recover Username</h1>
    <form action="username_recovery.php" method="POST">
        <label for="email">Ievadi savu e-pastu:</label><br>
        <input type="email" id="email" name="email" required><br>
        <button type="submit">Recover Username</button>
    </form>
</body>
</html>
