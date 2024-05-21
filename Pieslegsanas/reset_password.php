<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

if(isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token
    $sql = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $sql->bind_param("s", $token);
    $sql->execute();
    $result = $sql->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $new_password = password_hash($_POST['parole'], PASSWORD_DEFAULT);

            // Update password
            $sql = $conn->prepare("UPDATE lietotaji SET parole = ? WHERE epasts = ?");
            $sql->bind_param("ss", $new_password, $email);
            $sql->execute();

            // Delete the reset request
            $sql = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
            $sql->bind_param("s", $email);
            $sql->execute();

            $_SESSION['message'] = "Your password has been reset successfully.";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid or expired token.";
        header('Location: password_reset.php');
        exit();
    }
} else {
    header('Location: password_reset.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <link rel="stylesheet" href="pieslegsanas.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
</head>
<body class="mx-2">
  <main class="login-container">
    <hr>
    <form class="login-form"
          action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>"
          method="POST">

      <p class="login-title">Reset Password</p>

      <div class="input-group">
        <label  for="parole"
                class="input-label">
          New Password:
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
          Reset Password
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
      </div>
    </form>
  </main>
</body>
</html>
