<?php
  session_start();
  global $conn;

  // Check if the user is logged in
  if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
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
    $sql = $conn->prepare("SELECT id, vards, uzvards, epasts FROM lietotaji WHERE vards = ? AND uzvards = ?");
    $sql->bind_param('ss', $vards, $uzvards);
    list($vards, $uzvards) = explode(' ', $username);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        // Fetch and display profile information
        $profileInfo = $result->fetch_assoc();
        ?>
        <div class="border">
      <div class="items">
        <p>Vārds: <?php echo $profileInfo['vards']; ?></p>
        <p>Uzvārds: <?php echo $profileInfo['uzvards']; ?></p>
        <button class="button" id="followButton">Sekot</button>
        <!-- Example of a block button next to a user's comment -->
        <div class="comment">
    <p>This is a comment from <?php echo $profileInfo['vards'] . " " . $profileInfo['uzvards']; ?></p>
    <button class="block-btn" data-user-id="<?php echo $profileInfo['id']; ?>">Block <?php echo $profileInfo['vards'] . " " . $profileInfo['uzvards']; ?></button>
</div>

      </div>
        </div>
        <?php
    } else {
        echo "Profile not found.";
    }
} else {
    echo "Username not provided.";
}

?>

<script>
  document.querySelectorAll('.block-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var userId = this.dataset.userId;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'block_user.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle response if needed
            }
        };
        xhr.send('blocked_user_id=' + userId);
    });
});

</script>


<script>
document.getElementById("followButton").addEventListener("click", function() {
    // Send an AJAX request to the server to toggle follow status
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "follow_toggle.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response if needed
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Update button text or style based on the follow status
                var followButton = document.getElementById("followButton");
                followButton.textContent = response.isFollowing ? "Sekot" : "Nesekot";
            } else {
                // Handle error response
                console.error(response.error);
            }
        }
    };
    xhr.send(); // Send the request
});
</script>



<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Profils</title>
</head>
<body class="mx-2">
  <main class="main">
    <div class="border">
      <div class="items">
        
        <button class="button" onclick="atpakalIndex()">Atpakaļ</button>
        <a href="logout.php" class="logout">Atslēgties</a>
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
