<?php
session_start();

include 'datubaze.php';

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $epasts = $_POST['epasts'];
    $sql = $conn->prepare("SELECT * FROM lietotaji WHERE epasts = ?");
    $sql->bind_param("s", $epasts);
    $sql->execute();
    $result = $sql->get_result();

    if($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Insert token into the database
        $sql = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $epasts, $token, $expiry);
        $sql->execute();

        // Send email
        $resetLink = "http://localhost/reset_password.php?token=$token";
        $subject = "Password Reset";
        $message = "Click the following link to reset your password: $resetLink";
        $headers = "From: noreply@majaslapa.lv";

        mail($epasts, $subject, $message, $headers);

        $_SESSION['message'] = "Password reset link has been sent to your email.";
    } else {
        $_SESSION['error'] = "This email doesn't exist in our database.";
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
    <form class="login-form"
          action="password_reset.php"
          method="POST">

      <p class="login-title">Atjaunot paroli</p>

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

      <div class="button-group">
        <button class="login-button"
                type="submit">
          Sūtīt atjaunošanas saiti
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

            if(isset($_SESSION['message'])) {
              echo '
                      <div class="success-message">
                        '.$_SESSION['message'].'
                      </div>
                    ';
              unset($_SESSION['message']);
            }
          ?>
      </div>
    </form>
  </main>
</body>
</html>
