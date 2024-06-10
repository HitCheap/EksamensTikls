<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in.']);
    exit();
}

// Include your database connection code here
include 'datubaze.php';

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

$userId = $_SESSION['id'];
$newEmail = $_POST['new_email'];

// Fetch current encrypted email from the database
$sql = "SELECT epasts FROM lietotaji WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$currentEncryptedEmail = $row['epasts'];
$currentEmail = decrypt_email($currentEncryptedEmail);

// Check if new email is the same as the current email
if ($newEmail === $currentEmail) {
    echo json_encode(['success' => false, 'error' => 'The new email cannot be the same as the current email.']);
    exit();
}

// Encrypt the new email
$encryptedEmail = encrypt_email($newEmail);

// Update email
$updateEmailSql = "UPDATE lietotaji SET epasts = ? WHERE id = ?";
$updateEmailStmt = $conn->prepare($updateEmailSql);
$updateEmailStmt->bind_param("si", $encryptedEmail, $userId);
if ($updateEmailStmt->execute()) {
    echo json_encode(['success' => true, 'newEmail' => $newEmail]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
?>
