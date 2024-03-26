<?php
  session_start();

  $host = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'majaslapa';

  
  $conn = new mysqli($host, $username, $password, $database);

  
  if($conn -> connect_error) {
    echo 'Datubāzes pieslēgums neveiksmīgs.';
  }

  
  if($_SERVER['REQUEST_METHOD'] == "POST") {

      $epasts = $_POST['epasts'];
      $parole = $_POST['parole'];
  
      $sql = $conn -> prepare("SELECT * FROM lietotaji WHERE epasts = ?");
      $sql -> bind_param("s", $epasts);
      $sql -> execute();
      $sql -> bind_result($id, $epasts, $vards, $uzvards, $hashed_parole);

    
      if($sql -> fetch() && password_verify($parole, $hashed_parole)) {

       
        $_SESSION['id'] = $id;
        $_SESSION['epasts'] = $epasts;
        $_SESSION['vards'] = $vards;
        $_SESSION['uzvards'] = $uzvards;

        header('Location: index.php');
      } else {
        $_SESSION['error'] = "Nepareizi lietotāja dati. Lūdzu, mēģiniet vēlreiz.";
      }
    }
  
?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <link rel="stylesheet" href="style.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">


<!--  <script src="https://cdn.tailwindcss.com"></script>
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
      
    </form>
  </main>
</body>
</html>
