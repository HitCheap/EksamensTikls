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
        $_SESSION['error'] = "Visi obligātie lauki ir jāaizpilda.";
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

  <title>Majaslapa</title>
</head>
<body class="mx-2">
  <main class="login border rounded shadow-xl my-10 py-5 px-10 mx-auto max-w-[800px]
                flex flex-col">
    

    <hr>

  
    <form class="mt-5 flex flex-col justify-center gap-3"
          action="register.php"
          method="POST">

      <p class="text-xl text-center font-bold">REĢISTRĀCIJA</p>

      <div class="flex flex-col gap-2">
        <label  for="vards"
                class="font-semibold">
          Vārds:
        </label>
        <input type="text"
                id="vards"
                name="vards" 
                placeholder="Vārds"
                class="border rounded px-3 py-1 placeholder:italic outline-none"/>
      </div>

      <div class="flex flex-col gap-2">
        <label  for="uzvards"
                class="font-semibold">
          Uzvārds:
        </label>
        <input type="text"
                id="uzvards"
                name="uzvards" 
                placeholder="Uzvārds"
                class="border rounded px-3 py-1 placeholder:italic outline-none"/>
      </div>

      <div class="flex flex-col gap-2">
        <label  for="epasts"
                class="font-semibold">
          E-pasts*:
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
          Parole*:
        </label>
        <input type="password"
                id="parole"
                name="parole" 
                placeholder="••••••"
                class="border rounded px-3 py-1 placeholder:italic outline-none"/>
      </div>

      <div class="flex flex-col gap-2">
        <label  for="atkartoParoli"
                class="font-semibold">
          Atkārto Paroli*:
        </label>
        <input type="password"
                id="atkartoParoli"
                name="atkartoParoli" 
                placeholder="••••••"
                class="border rounded px-3 py-1 placeholder:italic outline-none"/>
      </div>

      <div class="flex flex-col gap-2">
        <button class="bg-gray-950 rounded text-white font-semibold tracking-wide
                        py-1 mt-5 hover:translate-y-0.5 duration-300 hover:bg-gray-800"
                type="submit">
          Reģistrēties
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

        <a href="login.php"
          class="text-end hover:underline italic text-xs">
          Esi reģistrējies?
        </a>
      </div>
      
    </form>
  </main>

</body>
</html>
