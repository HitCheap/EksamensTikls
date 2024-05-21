<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo 'Datubāzes pieslēgums neveiksmīgs.';
}

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

    $epasts = $_POST['epasts'];
    $encryptedEmail = encrypt_email($epasts); // Encrypt the email input before querying the database
    $parole = $_POST['parole'];

    $sql = $conn->prepare("SELECT id, epasts, lietotājvārds, parole, statuss FROM lietotaji WHERE epasts = ?");
    $sql->bind_param("s", $encryptedEmail);
    $sql->execute();
    $sql->bind_result($id, $dbEpasts, $lietotājvārds, $hashed_parole, $statuss);

    if ($sql->fetch() && password_verify($parole, $hashed_parole)) {
        if ($statuss === 'Deaktivizēts') {
            header("Location: ../deactivated.php");
            exit();
        } else {
            $_SESSION['id'] = $id;
            $_SESSION['epasts'] = $epasts; // Store the original email, not the hashed one
            $_SESSION['lietotājvārds'] = $lietotājvārds;

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
        <label  for="epasts"
                class="input-label">
          E-pasts:
        </label>
        <input type="email"
                id="epasts"
                name="epasts" 
                placeholder="epasts@gmail.com"
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
                placeholder="••••••"
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
        <a href="password_reset.php" class="forgot-password-link">Aizmirsi paroli?</a>
      </div>
      
    </form>
  </main>
</body>
</html>
