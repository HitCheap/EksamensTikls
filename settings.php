<?php
session_start();

if (!isset($_SESSION['id'])) {
  header('Location: login.php');
  exit();
}

include 'datubaze.php';
include 'navbar.php';

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

// Fetch current user details
$userId = $_SESSION['id'];
$sql = "SELECT epasts, pg13_mode FROM lietotaji WHERE id = ?";
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

// Initialize error message
$errorMessage = "";

// Change Password
if (isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if new password matches confirmation password
    if ($newPassword !== $confirmPassword) {
        $errorMessage = "New password and confirmation password do not match.";
    } elseif (strlen($newPassword) < 8) {
        $errorMessage = "New password must be at least 8 characters long.";
    } else {
        // Validate current password and check if new password is different
        $sql = "SELECT parole FROM lietotaji WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (password_verify($currentPassword, $row['parole'])) {
            if (password_verify($newPassword, $row['parole'])) {
                $errorMessage = "New password cannot be the same as the current password.";
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateSql = "UPDATE lietotaji SET parole = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("si", $hashedPassword, $userId);
                if ($updateStmt->execute()) {
                    session_destroy();
                    header("Location: login.php");
                    exit();
                } else {
                    $errorMessage = "Error updating password: " . $conn->error;
                }
            }
        } else {
            $errorMessage = "Current password is incorrect.";
        }
    }
}

// Delete Account
if (isset($_POST['delete_account'])) {
    $updateSql = "UPDATE lietotaji SET statuss = 'Deaktivizēts' WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("i", $userId);
    if ($updateStmt->execute()) {
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        $errorMessage = "Error updating account status: " . $conn->error;
    }
}

// Update Email
if (isset($_POST['new_email'])) {
    $newEmail = $_POST['new_email'];
    $encryptedEmail = encrypt_email($newEmail);

    $updateSql = "UPDATE lietotaji SET epasts = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $encryptedEmail, $userId);
    if ($updateStmt->execute()) {
        echo "Epasts veiskmīgi atjaunināts.";
        $currentEmail = $newEmail;
    } else {
        $errorMessage = "Kļūda atjauninot epastu: " . $conn->error;
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
    <style>
        .notification {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #f44336;
            color: white;
            padding: 16px;
            z-index: 1000;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="notification" id="notification"></div>

<div class="form-container">
  <div class="form-wrapper">
    <h2>Mainīt E-pastu</h2>
    <form id="changeEmailForm" method="POST">
        <label for="current_email">Pašreizējais E-pasts:</label>
        <input type="email" id="current_email" name="current_email" value="<?php echo htmlspecialchars($currentEmail); ?>" readonly><br>
        <label for="new_email">Jaunais E-pasts:</label>
        <input type="email" id="new_email" name="new_email" required><br>
        <button type="submit">Mainīt E-pastu</button>
    </form>
  </div>
  <div class="form-wrapper">
    <h2>Mainīt Paroli</h2>
    <form id="changePasswordForm" action="" method="POST">
        <label for="current_password">Pašreizējā Parole:</label>
        <input type="password" id="current_password" name="current_password" required><br>
        <label for="new_password">Jaunā Parole:</label>
        <input type="password" id="new_password" name="new_password" required><br>
        <label for="confirm_password">Apstiprināt Jauno Paroli:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        <button type="submit" name="change_password">Mainīt Paroli</button>
    </form>
  </div>
  <div class="form-wrapper">
    <h2>Deaktivizēt Kontu</h2>
    <form id="deleteAccountForm" action="" method="POST">
        <button type="submit" name="delete_account">Deaktivizēt Kontu</button>
    </form>
  </div>
</div>

<script>
document.getElementById('deleteAccountForm').addEventListener('submit', function(event) {
    var confirmation = confirm('Vai tiešām vēlaties deaktivizēt savu kontu?');
    if (!confirmation) {
        event.preventDefault();
    }
});

document.getElementById('changeEmailForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_email.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                document.getElementById('current_email').value = response.newEmail;
                showNotification('Epasts atjaunināts veiksmīgi.', 'success');
            } else {
                showNotification('Kļūda atjauninot epastu: ' + response.error, 'error');
            }
        }
    };

    var formData = new FormData(document.getElementById('changeEmailForm'));
    var params = new URLSearchParams();
    formData.forEach(function(value, key) {
        params.append(key, value);
    });

    xhr.send(params.toString());
});

document.getElementById('changePasswordForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var formData = new FormData(document.getElementById('changePasswordForm'));
    var params = new URLSearchParams();

    formData.forEach(function(value, key) {
        params.append(key, value);
    });

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_password.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            console.log(xhr.responseText); // Log the raw response
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showNotification(response.success, 'success');
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 2000);
                    } else {
                        showNotification(response.error, 'error');
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    showNotification('Kļūda parsējot atbildi no servera.', 'error');
                }
            } else {
                showNotification('Kļūda sazinoties ar serveri.', 'error');
            }
        }
    };
    xhr.send(params.toString());
});

function showNotification(message, type) {
    var notification = document.getElementById('notification');
    notification.textContent = message;
    notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';
    notification.style.display = 'block';
    setTimeout(function() {
        notification.style.display = 'none';
    }, 3000);
}


<?php if (!empty($errorMessage)): ?>
    showNotification('<?php echo addslashes($errorMessage); ?>', 'error');
<?php endif; ?>
</script>
<!-- <h2>PG-13 Mode</h2>
<form action="" method="POST">
    <button type="submit" name="toggle_pg13"><//?php echo $pg13_mode ? 'Disable PG-13 Mode' : 'Enable PG-13 Mode'; ?></button>
</form>

<script>
document.getElementById('changeEmailForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_email.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                document.getElementById('current_email').value = response.newEmail;
                alert('Email updated successfully.');
            } else {
                alert('Error updating email: ' + response.error);
            }
        }
    };

    var formData = new FormData(document.getElementById('changeEmailForm'));
    var params = new URLSearchParams();
    formData.forEach(function(value, key) {
        params.append(key, value);
    });

    xhr.send(params.toString());
});
</script> -->
</body>
</html>
