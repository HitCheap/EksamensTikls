<?php
include 'datubaze.php';

define('ENCRYPTION_KEY', 'your_encryption_key');
define('IV', '1234567890123456');

function encrypt_email($plainEmail) {
    $encryptedEmail = openssl_encrypt($plainEmail, 'aes-256-cbc', ENCRYPTION_KEY, 0, IV);
    if ($encryptedEmail === false) {
        return "Encryption failed.";
    }
    return $encryptedEmail;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $encryptedEmail = encrypt_email($email);

    $sql = $conn->prepare("SELECT lietotājvārds FROM lietotaji WHERE epasts = ?");
    $sql->bind_param("s", $encryptedEmail);
    $sql->execute();
    $sql->bind_result($lietotājvārds);

    if ($sql->fetch()) {
        $message = "Your username is: " . htmlspecialchars($lietotājvārds);
    } else {
        $message = "Email not found.";
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
    <title>Atjaunot Lietotājvārdu</title>
    <link rel="stylesheet" href="atgut.css">
</head>
<body>
    <h1>Atjaunot Lietotājvārdu</h1>
    <form action="username_recovery.php" method="POST">
        <label for="email">Ievadi savu e-pastu:</label><br>
        <input type="email" id="email" name="email" required><br>
        <button type="submit">Atjauno Lietotājvārdu</button>
        <button class="back-button" type="button" onclick="history.back()">Atpakaļ</button>
    </form>

    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>
