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
    
    if (empty($_POST['epasts']) || empty($_POST['parole']) || empty($_POST['atkartoParoli'])) {
        $_SESSION['error'] = "Visi lauki ir jāaizpilda.";
        header("Location: register.php");
        exit();
    }

    if (strlen($_POST['parole']) < 8) {
        $_SESSION['error'] = "Parolei jābūt vismaz 8 simbolu garai.";
        header("Location: register.php");
        exit();
    }

    if ($_POST['parole'] == $_POST['atkartoParoli']) {
        $lietotājvārds = $_POST['lietotājvārds'];
        $epasts = $_POST['epasts'];
        $encryptedEmail = encrypt_email($epasts); // Encrypt the email
        $parole = password_hash($_POST['parole'], PASSWORD_DEFAULT);
        $pfp = 'default.png';

        $sql = $conn->prepare("INSERT INTO lietotaji (lietotājvārds, epasts, parole, profile_picture) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $lietotājvārds, $encryptedEmail, $parole, $pfp);

        try {
            if ($sql->execute()) {
                header("Location: ../index.php");
                exit();
            } else {
                $_SESSION['error'] = "Reģistrācija neizdevās. Mēģiniet vēlreiz.";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $_SESSION['error'] = "Epasts jau eksistē. Izvēlaties citu epastu.";
            } else {
                $_SESSION['error'] = "Reģistrācija neizdevās. Mēģiniet vēlreiz.";
            }
        }
    } else {
        $_SESSION['error'] = "Paroles nesakrita. Mēģiniet vēlreiz.";
    }
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="pieslegsanas.css">
  <title>Majaslapa</title>
</head>
<body class="mx-2">
  <main class="register-container">
    <hr>
    <form class="register-form"
          action="register.php"
          method="POST">

      <p class="register-title">REĢISTRĀCIJA</p>

      <div class="input-group">
        <label  for="lietotājvārds"
                class="input-label">
        Lietotājvārds:
        </label>
        <input type="text"
                id="lietotājvārds"
                name="lietotājvārds" 
                placeholder="Lietotājvārds"
                class="input-field"/>
      </div>

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

      <div class="input-group">
        <label  for="atkartoParoli"
                class="input-label">
          Atkārto Paroli:
        </label>
        <input type="password"
                id="atkartoParoli"
                name="atkartoParoli" 
                placeholder="••••••"
                class="input-field"/>
      </div>

      <div class="button-group">
        <button class="login-button"
                type="submit">
          Reģistrēties
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

        <a href="login.php"
          class="login-link">
          Esi reģistrējies?
        </a>
      </div>
      
    </form>
  </main>

</body>
</html>
