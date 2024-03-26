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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    if (empty($_POST['epasts']) || empty($_POST['parole']) || empty($_POST['atkartoParoli'])) {
        $_SESSION['error'] = "Visi lauki ir jāaizpilda.";
        header("Location: register.php");
        exit();
    }

    if ($_POST['parole'] == $_POST['atkartoParoli']) {
        $vards = $_POST['vards'];
        $uzvards = $_POST['uzvards'];
        $epasts = $_POST['epasts'];
        $parole = password_hash($_POST['parole'], PASSWORD_DEFAULT);

        $sql = $conn->prepare("INSERT INTO lietotaji (vards, uzvards, epasts, parole) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $vards, $uzvards, $epasts, $parole);

        try {
            if ($sql->execute()) {
                header("Location: index.php");
            } else {
                $_SESSION['error'] = "Reģistrācija neizdevās. Mēģinat vēlreiz.";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $_SESSION['error'] = "Epasts jau eksistē. Izvēlaties citu epastu.";
            } else {
                $_SESSION['error'] = "Reģistrācija neizdevās. Mēģinat vēlreiz.";
            }
        }
    } else {
        $_SESSION['error'] = "Paroles nesakrita. Mēģinat vēlreiz.";
    }
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">

 <!-- <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            clifford: '#da373d',
          }
        }
      }
    }
  </script> -->

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
        <label  for="vards"
                class="input-label">
          Vārds:
        </label>
        <input type="text"
                id="vards"
                name="vards" 
                placeholder="Vārds"
                class="input-field"/>
      </div>

      <div class="input-group">
        <label  for="uzvards"
                class="input-label">
          Uzvārds:
        </label>
        <input type="text"
                id="uzvards"
                name="uzvards" 
                placeholder="Uzvārds"
                class="input-field"/>
      </div>

      <div class="input-group">
        <label  for="epasts"
                class="input-label">
          E-pasts:
        </label>
        <input type="text"
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
