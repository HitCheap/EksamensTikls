<?php
session_start();
global $conn;

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
    exit();
}

include 'database.php';

// Retrieve username from URL parameter
if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $nameParts = explode(' ', $username);

    if (count($nameParts) === 1) {
        list($lietotājvārds) = $nameParts;

        // Query to fetch profile information based on username
        $sql = $conn->prepare("SELECT id, lietotājvārds, statuss FROM lietotaji WHERE lietotājvārds = ?");
        $sql->bind_param('s', $lietotājvārds);
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

            // Check if the logged-in user has blocked this profile
            $sql3 = $conn->prepare("SELECT * FROM blocked_users WHERE user_id = ? AND blocked_user_id = ?");
            $sql3->bind_param('ii', $_SESSION['id'], $profileId);
            $sql3->execute();
            $blockResult = $sql3->get_result();
            $isBlocked = $blockResult->num_rows > 0;
            ?>
            <div class="border">
                <div class="items">
                    <p>Lietotājvārds: <?php echo htmlspecialchars($profileInfo['lietotājvārds']); ?></p>
                    <button class="button" onclick="atpakalIndex()">Atpakaļ</button>

                    <?php
                    if (isset($_SESSION['statuss']) && $_SESSION['statuss'] === 'Administrators') {
                        echo "User is an administrator.";
                        echo '<button class="delete-btn" onclick="confirmDeleteUser()">Delete User</button>';
                    }

                    function confirmDeleteUser() {
                        if (confirm("Are you sure you want to delete this user?")) {
                            // Delete user logic here
                            $userId = $_GET['user_id']; // Get the user ID from the URL or session
                            $sql = $conn->prepare("DELETE FROM lietotaji WHERE id =?");
                            $sql->bind_param("i", $userId);
                            $sql->execute();
                            echo "User deleted successfully!";
                        } else {
                            echo "Deletion cancelled.";
                        }
                        }
                    ?>

                    <a href="logout.php" class="logout">Atslēgties</a>
                    <?php if (!$isBlocked) { ?>
                        <button class="button" id="followButton" data-followed-id="<?php echo $profileId; ?>"><?php echo $isFollowing ? 'Nesekot' : 'Sekot'; ?></button>
                    <?php } ?>
                    <div class="comment">
                        <button class="block-btn" data-user-id="<?php echo $profileInfo['id']; ?>"><?php echo $isBlocked ? 'Atbloķēt' : 'Bloķēt'; ?> <?php echo htmlspecialchars($profileInfo['lietotājvārds']); ?></button>
                    </div>
                    <div id="messagesContainer">
                        <?php
                        // Fetch user comments
                        $commentsSql = $conn->prepare("SELECT teksts, datums FROM komentari WHERE lietotaja_id = ? ORDER BY datums DESC LIMIT 10");
                        $commentsSql->bind_param('i', $profileId);
                        $commentsSql->execute();
                        $commentsResult = $commentsSql->get_result();

                        if ($commentsResult->num_rows > 0) {
                            while ($comment = $commentsResult->fetch_assoc()) {
                                echo '<div class="message">';
                                echo '<p>' . htmlspecialchars($comment['teksts']) . '</p>';
                                echo '<small>' . date('g:i A l, F j, Y', strtotime($comment['datums'])) . '</small>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No comments to display.</p>';
                        }
                        ?>
                    </div>
                    <button id="seeMoreButton" onclick="loadMoreMessages(<?php echo $profileId; ?>)">See more</button>
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

document.querySelectorAll('.block-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var userId = this.dataset.userId;
        var isBlocking = this.textContent.startsWith('Bloķēt');
        
        if (isBlocking) {
            var confirmation = confirm(`Vai tiešām vēlaties bloķēt šo lietotāju?`);
            if (!confirmation) {
                return; // Exit the function if the user cancels
            }
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'block_user.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    var blockButton = document.querySelector('.block-btn[data-user-id="' + userId + '"]');
                    blockButton.textContent = response.isBlocked ? 'Atbloķēt ' + response.username : 'Bloķēt ' + response.username;
                    location.reload(); // Reload the page to update follow button visibility
                } else {
                    console.error(response.error);
                }
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
                if (response.error === 'You cannot follow a blocked user') {
                    alert('You cannot follow a blocked user');
                } else {
                    console.error(response.error);
                }
            }
        }
    };
    xhr.send('followed_id=' + followedId);
});


let offset = 10; // Start with the next set of messages
const limit = 10;

function loadMoreMessages(profileId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', `load_more_messages.php?profile_id=${profileId}&offset=${offset}&limit=${limit}`, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            var messagesContainer = document.getElementById('messagesContainer');
            if (response.messages.length > 0) {
                response.messages.forEach(function(message) {
                    var messageDiv = document.createElement('div');
                    messageDiv.classList.add('message');
                    messageDiv.innerHTML = `<p>${message.teksts}</p><small>${new Date(message.datums).toLocaleString()}</small>`;
                    messagesContainer.appendChild(messageDiv);
                });
                offset += limit;
            }
            if (!response.hasMore) {
                var seeMoreButton = document.getElementById('seeMoreButton');
                seeMoreButton.style.display = 'none';
            }
        }
    };
    xhr.send();
}
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