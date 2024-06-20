<?php
include 'datubaze.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
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

$profilePicture = !empty($user['profile_picture']) ? $user['profile_picture'] : 'bildes/default.png';

if ($profilePicture === 'bildes/default.png') {
    $profilePicturePath = './' . $profilePicture;
} else {
    $profilePicturePath = './profile_pictures/' . $profilePicture;
}

if (!file_exists($profilePicturePath) || empty($profilePicture)) {
    $profilePicturePath = './bildes/default.png'; // Ensure this path is correct
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <p class="navbar-text">
                Sveicināti, 
                <img src="<?php echo htmlspecialchars($profilePicturePath); ?>" alt="Profile Picture" class="profile-picture inline-image">
                <?php echo htmlspecialchars($_SESSION['lietotājvārds']); ?> 
                <?php if ($isAdmin) { ?>
                    (<?php echo $user['statuss']; ?>)!
                <?php } ?>
            </p>
        </div>
        <div class="navbar-right">
            <button class="navbar-button" onclick="redirectToStart()">Sākums</button>
            <button class="navbar-button" onclick="redirectToProfile('<?php echo htmlspecialchars($_SESSION['lietotājvārds']); ?>')">Profils</button>
            <button class="navbar-button" onclick="redirectToSettings()">Iestatījumi</button>
            <button class="navbar-button" onclick="redirectToNoti()">Paziņojumi</button>
            <button class="navbar-button" onclick="redirectToChat()">Čats</button>
            <button class="navbar-button" onclick="redirectToGames()">Spēles</button>
        </div>
        <div class="navbar-right">
            <a href="logout.php" class="navbar-link logout">Atslēgties</a>
        </div>
    </div>

    <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') { ?>
        <div class="comment-form-container">
            <form id="commentForm" action="komentars.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="teksts" placeholder="Raksti komentāru" id="commentText" class="publicet-text" oninput="enableButton()" />
                <input type="file" name="media" id="mediaInput" accept="image/*,video/*,audio/*" onchange="enableButton()" />
                <div class="text-end">
                    <button id="submitButton" class="publicet" onclick="validateAndSubmit()" disabled>Publicēt</button>
                </div>
            </form>
        </div>
    <?php } ?>

    <script src="script.js"></script>
</body>
</html>
