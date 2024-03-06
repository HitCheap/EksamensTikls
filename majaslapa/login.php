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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">


  <script src="https://cdn.tailwindcss.com"></script>
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
  </script>

  <title>Mājaslapa</title>
</head>
<body class="mx-2">
  <main class="login border rounded shadow-xl my-10 py-16 px-10 mx-auto max-w-[800px]
                flex flex-col">
    <hr>

   
    <form class="mt-5 flex flex-col justify-center gap-3"
          action="login.php"
          method="POST">

      <p class="text-xl text-center font-bold">Pieslēgties</p>

      <div class="flex flex-col gap-2">
        <label  for="epasts"
                class="font-semibold">
          E-pasts:
        </label>
        <input type="text"
                id="epasts"
                name="epasts" 
                placeholder="epasts@gmail.com"
                class="border rounded px-3 py-1 placeholder:italic outline-none"/>
      </div>

      <div class="flex flex-col gap-2">
        <label  for="parole"
                class="font-semibold">
          Parole:
        </label>
        <input type="password"
                id="parole"
                name="parole" 
                placeholder="••••••"
                class="border rounded px-3 py-1 placeholder:italic outline-none"/>
      </div>

      <div class="flex flex-col gap-2">
        <button class="bg-gray-950 rounded text-white font-semibold tracking-wide
                        py-1 mt-5 hover:translate-y-0.5 duration-300 hover:bg-gray-800"
                type="submit">
          Pieslēgties
        </button>

        <?php
            if(isset($_SESSION['error'])) {
              echo '
                      <div class="bg-red-200 rounded text-center p-2 w-full text-xs mt-3 italic">
                        '.$_SESSION['error'].'
                      </div>
                    ';
              unset($_SESSION['error']);
            }
          ?>

        <a href="register.php"
          class="text-end hover:underline italic text-xs">
          Neesi reģistrējies?
        </a>
      </div>
      
    </form>
  </main>
</body>
</html>
