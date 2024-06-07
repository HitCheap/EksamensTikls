<?php
session_start();

include '../database.php';

// Encryption/Decryption constants
define('ENCRYPTION_KEY', 'your_encryption_key'); // Replace with your actual key
define('IV', '1234567890123456'); // 16-byte IV

function encrypt_email($plainEmail) {
    return openssl_encrypt($plainEmail, 'aes-256-cbc', ENCRYPTION_KEY, 0, IV);
}

function decrypt_email($encryptedEmail) {
    return openssl_decrypt($encryptedEmail, 'aes-256-cbc', ENCRYPTION_KEY, 0, IV);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $identifier = $_POST['identifier']; // Can be either email or username
    $parole = $_POST['parole'];

    // Check if the identifier is an email
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        $encryptedEmail = encrypt_email($identifier);
        $sql = $conn->prepare("SELECT id, epasts, lietotājvārds, parole, statuss FROM lietotaji WHERE epasts = ?");
        $sql->bind_param("s", $encryptedEmail);
    } else {
        // Assume it's a username
        $sql = $conn->prepare("SELECT id, epasts, lietotājvārds, parole, statuss FROM lietotaji WHERE lietotājvārds = ?");
        $sql->bind_param("s", $identifier);
    }

    $sql->execute();
    $sql->bind_result($id, $dbEpasts, $lietotājvārds, $hashed_parole, $statuss);

    if ($sql->fetch() && password_verify($parole, $hashed_parole)) {
        if ($statuss === 'Deaktivizēts') {
            header("Location: ../deactivated.php");
            exit();
        } else {
            $_SESSION['id'] = $id;
            $_SESSION['epasts'] = decrypt_email($dbEpasts); // Store the decrypted email
            $_SESSION['lietotājvārds'] = $lietotājvārds;

            // Make secret.php accessible for the first 10 seconds after login
            $_SESSION['show_secret'] = true;
            header('Location: ../index.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Nepareizi lietotāja dati. Lūdzu, mēģiniet vēlreiz.";
        header('Location: login.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <link rel="stylesheet" href="pieslegsanas.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mājaslapa</title>
</head>
<body class="mx-2">
  <main class="login-container">
    <hr>

    <form class="login-form"
          action="login.php"
          method="POST">

      <p class="login-title">PIESLĒGTIES</p>

      <div class="input-group">
        <label  for="identifier"
                class="input-label">
          E-pasts vai Lietotājvārds:
        </label>
        <input type="text"
                id="identifier"
                name="identifier" 
                placeholder="epasts@gmail.com vai Lietotājvārds"
                class="input-field"/>
      </div>

      <div class="input-group">
        <label  for="parole"
                class="input-label">
          Parole:
        </label>
        <input type="password"
                id="parole"
                name="parole" 
                placeholder="••••••••"
                class="input-field"/>
      </div>

      <div class="button-group">
        <button class="login-button"
                type="submit">
          Pieslēgties
        </button>

        <?php
            if(isset($_SESSION['error'])) {
              echo '
                      <div class="error-message">
                        '.$_SESSION['error'].'
                      </div>
                    ';
              unset($_SESSION['error']);
            }
          ?>

        <a href="register.php"
          class="register-link">
          Neesi reģistrējies?
        </a>
      </div>

      <div class="button-group">
        <a href="recover.php" class="forgot-password-link">Aizmirsi paroli vai lietotājvārdu?</a>
      </div>
      
    </form>
  </main>

  <?php
  // Include a script to make secret.php visible temporarily
  if (isset($_SESSION['show_secret'])) {
      echo '<script>
          setTimeout(function() {
              var xhr = new XMLHttpRequest();
              xhr.open("GET", "secret.php", true);
              xhr.send();
          }, 1000); // 1 second after login

          setTimeout(function() {
              var xhr = new XMLHttpRequest();
              xhr.open("GET", "remove_secret.php", true);
              xhr.send();
          }, 10000); // Remove after 10 seconds
      </script>';
      unset($_SESSION['show_secret']);
  }
  ?>
</body>
</html>
