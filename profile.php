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

// Retrieve username from URL parameter
if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $nameParts = explode(' ', $username);

    if (count($nameParts) === 2) {
        list($vards, $uzvards) = $nameParts;

        // Query to fetch profile information based on username
        $sql = $conn->prepare("SELECT id, vards, uzvards, epasts FROM lietotaji WHERE vards = ? AND uzvards = ?");
        $sql->bind_param('ss', $vards, $uzvards);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            // Fetch and display profile information
            $profileInfo = $result->fetch_assoc();
            $profileId = $profileInfo['id'];
            
            // Check if the logged-in user is already following this profile
            $sql2 = $conn->prepare("SELECT * FROM follows WHERE follower_id = ? AND followed_id = ?");
            $sql2->bind_param('ii', $_SESSION['id'], $profileId);
            $sql2->execute();
            $followResult = $sql2->get_result();
            $isFollowing = $followResult->num_rows > 0;
            ?>
            <div class="border">
                <div class="items">
                    <p>Vārds: <?php echo htmlspecialchars($profileInfo['vards']); ?></p>
                    <p>Uzvārds: <?php echo htmlspecialchars($profileInfo['uzvards']); ?></p>
                    <p>Epasts: <?php echo htmlspecialchars($profileInfo['epasts']); ?></p>
                    <button class="button" onclick="atpakalIndex()">Atpakaļ</button>
                    <button class="button" onclick="piezimes()">Piezīmju grāmatiņa</button>
                    <a href="logout.php" class="logout">Atslēgties</a>
                    <?php if ($profileId !== $_SESSION['id']) { ?>
                        <button class="button" id="followButton" data-followed-id="<?php echo $profileId; ?>"><?php echo $isFollowing ? 'Nesekot' : 'Sekot'; ?></button>
                    <?php } ?>
                    <?php if ($isFollowing || $profileId === $_SESSION['id']) { ?>
                        <div class="comment">
                            <p>This is a comment from <?php echo htmlspecialchars($profileInfo['vards'] . " " . $profileInfo['uzvards']); ?></p>
                            <?php if ($profileId !== $_SESSION['id']) { ?>
                                <button class="block-btn" data-user-id="<?php echo $profileInfo['id']; ?>">Block <?php echo htmlspecialchars($profileInfo['vards'] . " " . $profileInfo['uzvards']); ?></button>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php
        } else {
            echo "Profile not found.";
        }
    } else {
        echo "Invalid username format.";
    }
} else {
    echo "Username not provided.";
}
?>

<script>
function atpakalIndex() {
    window.location.href = 'index.php';
}

function piezimes() {
    window.location.href = 'notepad.php';
}

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

document.getElementById("followButton").addEventListener("click", function() {
    var followedId = this.getAttribute('data-followed-id');
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "follow_toggle.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                var followButton = document.getElementById("followButton");
                followButton.textContent = response.isFollowing ? "Nesekot" : "Sekot";
                location.reload(); // Reload the page to update comment visibility
            } else {
                console.error(response.error);
            }
        }
    };
    xhr.send('followed_id=' + followedId);
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
        <!-- Profile content will be loaded here -->
    </main>
</body>
</html>
