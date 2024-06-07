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
        $sql = $conn->prepare("SELECT id, lietotājvārds, profile_picture FROM lietotaji WHERE lietotājvārds = ?");
        $sql->bind_param('s', $lietotājvārds);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            // Fetch and display profile information
            $profileInfo = $result->fetch_assoc();
            $profileId = $profileInfo['id'];
            $profilePicture = $profileInfo['profile_picture'];
             // Display user profile
//    echo '<img src="profile_pictures/' . htmlspecialchars($profilePicture) . '" alt="Profile Picture">';
//    echo '<h1>' . htmlspecialchars($profileInfo['lietotājvārds']) . '</h1>';
    // Include form to change profile picture
//    echo '<form action="upload_profile_picture.php" method="POST" enctype="multipart/form-data">
//            <input type="file" name="profile_picture" accept="image/*" required>
//            <button type="submit">Change Profile Picture</button>
//          </form>';

            // Check if the profile is the current user's profile
            $isCurrentUser = ($profileId == $_SESSION['id']);

            if (!$isCurrentUser) {
                header("Location: diff_profile.php?username=$username");
                exit();
            }
            
            // Check if the logged-in user is already following this profile
            $sql2 = $conn->prepare("SELECT * FROM follows WHERE follower_id = ? AND followed_id = ?");
            $sql2->bind_param('ii', $_SESSION['id'], $profileId);
            $sql2->execute();
            $followResult = $sql2->get_result();
            $isFollowing = $followResult->num_rows > 0;

            $showEasterEggTooltip = substr($profileInfo['lietotājvārds'], -1) === '*';
            ?>
            <!DOCTYPE html>
            <html lang="lv">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="style.css">
                <title>Profils</title>
                <style>
        .star-tooltip {
            position: relative;
            cursor: pointer;
        }
        .star-tooltip:hover::after {
            content: "Easter egg finder";
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            white-space: nowrap;
        }
    </style>
            </head>
            <body class="mx-2">
                <main class="main">
                    <div class="border">
                        <div class="items">
                            <p>Lietotājvārds: <?php echo htmlspecialchars($profileInfo['lietotājvārds']); ?>
                        
                            <?php if ($showEasterEggTooltip): ?>
                            <span class="star-tooltip">*</span>
                            <?php endif; ?>
                            </p>
                            <button class="button" onclick="atpakalIndex()">Atpakaļ</button>
                            <button class="button" onclick="piezimes()">Piezīmju grāmatiņa</button>
                            <a href="logout.php" class="logout">Atslēgties</a>
                            <?php if ($profileId !== $_SESSION['id']) { ?>
                                <button class="button" id="followButton" data-followed-id="<?php echo $profileId; ?>"><?php echo $isFollowing ? 'Nesekot' : 'Sekot'; ?></button>
                            <?php } ?>
                            <?php if ($isFollowing || $profileId === $_SESSION['id']) { ?>
                            <?php } ?>
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
                </main>
            </body>
            </html>
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
