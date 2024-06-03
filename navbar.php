<?php

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Datubāzes pieslēgums neveiksmīgs: ' . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
    exit();
}


// Fetch user information
$userId = $_SESSION['id'];
$sql = $conn->prepare("SELECT statuss, profile_picture FROM lietotaji WHERE id = ?");
$sql->bind_param("i", $userId);
$sql->execute();
$result = $sql->get_result();
$user = $result->fetch_assoc();

// Check if the user is an administrator
$isAdmin = $user['statuss'] === 'Administrators';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Example</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
        <p class="navbar-text">
        Sveicināti, 
        <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="profile-picture inline-image">
        <?php echo $_SESSION['lietotājvārds']; ?> (<?php echo $user['statuss']; ?>)!
        </p>
        </div>
        <div class="navbar-right">
            <button class="navbar-button" onclick="redirectToProfile('<?php echo $_SESSION['lietotājvārds']; ?>')">Profils</button>
            <button class="navbar-button" onclick="redirectToSettings()">Iestatījumi</button>
            <a href="notification.php" class="navbar-link">Notifications</a>
            <button class="navbar-button" onclick="redirectToGames()">Spēles</button>
        </div>
        <div class="navbar-right">
            <a href="Pieslegsanas/logout.php" class="navbar-link logout">Atslēgties</a>
        </div>
    </div>

    <div class="comment-form-container">
        <form id="commentForm" action="komentars.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="teksts" placeholder="Raksti komentāru" id="commentText" class="publicet-text" oninput="enableButton()" />
            <input type="file" name="media" id="mediaInput" accept="image/*,video/*,audio/*" onchange="enableButton()" />
            <div class="text-end">
                <button id="submitButton" class="publicet" onclick="validateAndSubmit()" disabled>Publicēt</button>
            </div>
        </form>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
