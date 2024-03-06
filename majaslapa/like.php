<?php
session_start();

// Database connection code (similar to your other PHP files)
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
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$userID = $_SESSION['id'];

// Check if the user already liked
// You need to implement your logic here based on your database structure

// Assuming you have a table 'likes_table' with columns 'user_id' and 'post_id'
$postID = $_POST['post_id'];  // You need to pass post_id from your JavaScript

// Check if the user already liked the post
$sql = $conn->prepare("SELECT * FROM likes_table WHERE user_id = ? AND post_id = ?");
$sql->bind_param("ii", $userID, $postID);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    // User already liked, remove the like
    $deleteSQL = $conn->prepare("DELETE FROM likes_table WHERE user_id = ? AND post_id = ?");
    $deleteSQL->bind_param("ii", $userID, $postID);
    $deleteSQL->execute();
    echo json_encode(['action' => 'unlike']);
} else {
    // User has not liked, add the like
    $insertSQL = $conn->prepare("INSERT INTO likes_table (user_id, post_id) VALUES (?, ?)");
    $insertSQL->bind_param("ii", $userID, $postID);
    $insertSQL->execute();
    echo json_encode(['action' => 'like']);
}

// Close the database connection
$conn->close();
?>


<!-- Add this script to your HTML file -->
<script>
    function handleLike(postID) {
        // Send an Ajax request to like.php
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'like.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                // Handle the response
                if (response.error) {
                    console.error(response.error);
                } else {
                    // Update the UI based on the response
                    const likeButton = document.getElementById(`likeButton_${postID}`);
                    const likeCount = document.getElementById(`likeCount_${postID}`);

                    if (response.action === 'like') {
                        likeButton.innerText = 'Unlike';
                        likeCount.innerText = parseInt(likeCount.innerText) + 1;
                    } else if (response.action === 'unlike') {
                        likeButton.innerText = 'Like';
                        likeCount.innerText = parseInt(likeCount.innerText) - 1;
                    }
                }
            }
        };
        xhr.send(`post_id=${postID}`);
    }
</script>

<!-- Your post with like button and like count -->
<div>
    <button id="likeButton_1" onclick="handleLike(1)">Like</button>
    <span id="likeCount_1">0</span>
</div>

<!-- Repeat the structure for each post, updating the IDs accordingly -->
<div>
    <button id="likeButton_2" onclick="handleLike(2)">Like</button>
    <span id="likeCount_2">0</span>
</div>
