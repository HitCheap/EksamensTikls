<?php
  session_start();
  global $conn;

  // Check if the user is logged in
  if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
  }
  $host = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'majaslapa';

  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

?>


<?php



// Retrieve username from URL parameter
if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Query to fetch profile information based on username
    $sql = $conn->prepare("SELECT vards, uzvards, epasts FROM lietotaji WHERE vards = ? AND uzvards = ?");
    $sql->bind_param('ss', $vards, $uzvards);
    list($vards, $uzvards) = explode(' ', $username);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        // Fetch and display profile information
        $profileInfo = $result->fetch_assoc();
        ?>
        <p>Vārds: <?php echo $profileInfo['vards']; ?></p>
        <p>Uzvārds: <?php echo $profileInfo['uzvards']; ?></p>
        <p>E-pasts: <?php echo $profileInfo['epasts']; ?></p>
        <?php
    } else {
        echo "Profile not found.";
    }
} else {
    echo "Username not provided.";
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
  <main class="login border rounded shadow-xl my-10 p-5 mx-auto max-w-[1000px] min-h-[90vh] flex flex-col">
    <div class="border my-5 p-5 rounded">
      <div class="flex justify-between items-center">
      </div>
      <div class="flex justify-between items-center">
        
        <button onclick="atpakalIndex()">Atpakal</button>
        <a href="logout.php" class="hover:underline text-sm font-semibold cursor-pointer">Atslēgties</a>
      </div>

      <?php
      $sql2 = $conn->prepare("SELECT vards, uzvards, epasts FROM lietotaji WHERE id = ?");
        $sql2->bind_param('s', $rinda['lietotaja_id']);
        $sql2->execute();

        $profileInfo = [
            'vards' => $_SESSION['vards'],
            'uzvards' => $_SESSION['uzvards'],
            'epasts' => $_SESSION['epasts']
          ];
        ?>
        <p>Vārds: <?php echo $profileInfo['epasts']; ?></p>
        <p>Uzvārds: <?php echo $profileInfo['vards']; ?></p>
        <p>E-pasts: <?php echo $profileInfo['uzvards']; ?></p>
    </div>
    <script>
        function atpakalIndex() {
      window.location.href = 'index.php';
    }
    </script>
  </main>
</body>
</html>
