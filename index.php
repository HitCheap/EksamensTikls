<?php

  session_start();
  include 'search_users.php';
  // After successful login
$user_id = $_SESSION['id']; // Retrieve user ID from database or other source
$_SESSION['user_id'] = $user_id; // Store user ID in session variable
  // After successful login
  $_SESSION['lietotaji_id'] = $user_id; // Set the user ID in the session

  if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
  }
  
  $host = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'majaslapa';

  $conn = new mysqli($host, $username, $password, $database);

  function attelotKomentarus() {
    global $conn;
    $currentUserId = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    $sql = "SELECT k.*, l.vards, l.uzvards
            FROM komentari k
            INNER JOIN lietotaji l ON k.lietotaja_id = l.id
            LEFT JOIN blocked_users b ON k.lietotaja_id = b.blocked_user_id AND b.user_id = ?
            WHERE b.id IS NULL
            ORDER BY k.datums DESC
            LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $currentUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $commentId = $row['comment_id'];
            $commentText = $row['teksts'];
            $photo = $row['photo'];

            echo '<div class="comment-container" id="comment_' . $commentId . '">';
            echo '<p class="profile-link" onclick="redirectToProfile(\'' . $row['vards'] . ' ' . $row['uzvards'] . '\')">' . $row['vards'] . ' ' . $row['uzvards'] . '</p>';
            echo '<small class="date">' . date_format(date_create($row['datums']), "g:i A l, F j, Y") . '</small>';
            echo '<p class="fonts">' . $commentText . '</p>';

            if ($photo) {
                echo '<img src="' . $photo . '" alt="Uploaded photo" class="comment-photo">';
            }

            echo '<div class="like-container" data-post-id="' . $commentId . '"> 
                    <button id="likeButton_' . $commentId . '" onclick="handleLike(' . $commentId . ')" class="like-btn">
                        Patīk
                        <span class="like-count">0</span>
                    </button>
                  </div>';

            echo '<button class="comment-btn" onclick="openModal(' . $commentId . ')">Atbildēt</button>';
            echo '<button class="reply-btn" id="repostButton">Pārpublicēt</button>';

            if (isset($_SESSION['id']) && $_SESSION['id'] == $row['lietotaja_id']) {
                echo '<button class="delete-btn" onclick="confirmDelete(' . $commentId . ')">
                        Dzēst
                      </button>';
                echo '<div class="comment-container" data-comment-id="' . $commentId . '">
                        <div class="comment-text">' . $commentText . '</div>
                        <button class="edit-btn" onclick="openEditModal(' . $commentId . ')">Rediģēt</button>
                        <span class="edited-label" style="display:none;">(Rediģēts)</span>
                      </div>';
            }

            echo '</div>'; // Close the comment-container
        }
    } else {
        echo '<p class="nav">Nav pieejamu komentāru.</p>';
    }

    $stmt->close();
}


  function isBlocked($currentUserId, $commenterId) {
    global $conn;

    // Query to check if the commenter is blocked by the current user
    $sql = "SELECT COUNT(*) AS blocked 
            FROM blocked_users 
            WHERE user_id = ? AND blocked_user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $currentUserId, $commenterId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['blocked'] > 0;
}
    // Assuming you have a likes_table with columns: like_id, post_id, user_id

// Display comments
// if ($rezultats->num_rows > 0) {
//  while ($rinda = $rezultats->fetch_assoc()) {
//      $commentId = $rinda['comment_id'];
//      $commentText = $rinda['teksts'];

      // Fetch the number of likes for this comment
//      $sqlLikes = "SELECT COUNT(*) AS like_count FROM likes_table WHERE post_id = $commentId";
//      $likeResult = $conn->query($sqlLikes);
//      $likeCount = ($likeResult->num_rows > 0) ? $likeResult->fetch_assoc()['like_count'] : 0;

      // Output comment HTML
//      echo '<div class="comment" id="comment_' . $commentId . '">';
//      echo '<p>' . $commentText . '</p>';
//      echo '<button onclick="handleLike(' . $commentId . ')">Like (' . $likeCount . ')</button>';
//      echo '</div>';
//  }
//}

?>

<script>
    // Listen for click event on the repost button
    document.getElementById("repostButton").addEventListener("click", function() {
        // Send an AJAX request to the server to repost the content
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "repost.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle the response from the server
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Update button text or style based on the repost status
                    var repostButton = document.getElementById("repostButton");
                    repostButton.textContent = response.isReposted ? "Atcelt pārpublicēšanu" : "Pārpublicēt";
                } else {
                    // Handle error response
                    console.error(response.error);
                }
            }
        };
        xhr.send(); // Send the AJAX request
    });

</script>

<script>
        function enableButton() {
            const commentText = document.getElementById('commentText').value;
            document.getElementById('submitButton').disabled = commentText.trim() === '';
        }

        function validateAndSubmit() {
            // Additional validation can be added here
            return true;
        }
    </script>

<script>
function confirmDelete(commentId) {
  if (confirm("Vai tiešām vēlaties dzēst šo komentāru?")) {
    // User clicked "OK"
    deleteComment(commentId);
  } else {
    // User clicked "Cancel"
    // Do nothing
  }
}

function deleteComment(commentId) {
  // AJAX request to delete the comment
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "delete_comment.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Response received
      var response = JSON.parse(xhr.responseText);
      if (response.success) {
        // Comment deleted successfully
        // Optionally, you can update the UI to reflect the deletion
        location.reload(); // Refresh the page
      } else {
        // Error deleting the comment
        alert("Radās kļūda dzēšot komentāru");
      }
    }
  };
  xhr.send("comment_id=" + commentId);
}
</script>


<!-- Add this script to your HTML file -->
<script>
// JavaScript code to handle like/unlike functionality and update the UI

// Function to handle like/unlike action
function handleLike(postID) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'like.php'); // Assuming this file is like.php
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.error) {
                // Handle error
                console.error(response.error);
            } else {
                // Update like/unlike button text
                var likeButton = document.getElementById('likeButton_' + postID);
                var likeCount = document.getElementById('likeCount_' + postID);
                if (response.action === 'patīk') {
                    likeButton.textContent = 'Atcelt patīk';
                } else if (response.action === 'atcelt patīk') {
                    likeButton.textContent = 'Patīk';
                }
                // Update like count
                likeCount.textContent = response.like_count;
            }
        } else {
            // Handle error
            console.error(xhr.statusText);
        }
    };
    xhr.onerror = function() {
        // Handle error
        console.error('Request failed');
    };
    xhr.send('post_id=' + encodeURIComponent(postID));
}

// Attach click event handler to like buttons
var likeButtons = document.querySelectorAll('.like-btn');
likeButtons.forEach(function(likeButton) {
    likeButton.addEventListener('click', function() {
        var postID = this.parentNode.getAttribute('data-post-id');
        handleLike(postID);
    });
});

</script>

<script>
    // JavaScript to open a new tab when a comment link is clicked
    function openCommentsTab(commentId) {
        window.open('view_comments.php?comment_id=' + commentId, '_blank');
    }
</script>

<!-- Your post with like button and like count -->
<!--  <div>
      <button id="likeButton_1" onclick="handleLike(1)">Like</button>
      <span id="likeCount_1">0</span>
  </div> -->

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <script src="script.js"></script>
  <title>Sākumlapa</title>
</head>
<body class="mx-2">
  <main class="main">
    <div class="border">
      <div class="items">
        <p class="text">Sveicinati, <?php echo $_SESSION['epasts'] ?>!</p>
        <button class="button" onclick="redirectToProfile()">Profils</button>
        <button class="button" onclick="redirectToSettings()">Iestatījumi</button>
        <!-- Add this in your main navigation HTML -->
        <a href="notification.php">Notifications</a>
        <button class="button" onclick="redirectToGames()">Spēles</button>
        <a href="Pieslegsanas/logout.php" class="logout">Atslēgties</a>
      </div>
      <form action="komentars.php" method="POST" class="comment" enctype="multipart/form-data">
        <input type="text" name="teksts" placeholder="Raksti komentāru" id="commentText" class="publicet-text" oninput="enableButton()"/>
        <input type="file" name="photo" id="photo" accept="image/*">
        <div class="text-end">
<button id="submitButton" class="publicet" onclick="validateAndSubmit()" disabled>
    Publicēt
</button>

<script>
    function enableButton() {
        var commentText = document.getElementById('commentText').value.trim();
        var submitButton = document.getElementById('submitButton');
        if (commentText !== '') {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    function validateAndSubmit() {
        var commentText = document.getElementById('commentText').value.trim();
        if (commentText === '') {
            alert('Comment text cannot be empty');
        } else {
            // If comment text is not empty, submit the form
            document.getElementById('commentForm').submit();
        }
    }
</script>

        </div>
      </form>
    </div>
    <?php
      attelotKomentarus();
    ?>
    <?php


  if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
  }

  $profileInfo = [
    'vards' => $_SESSION['vards'],
    'uzvards' => $_SESSION['uzvards'],
    'epasts' => $_SESSION['epasts']
  ];
?>



<script>
    function validateAndSubmit() {
        // Get the comment text value and trim any whitespace
        var commentText = document.getElementById('commentText').value.trim();
        
        // Check if the comment text is empty
        if (commentText === '') {
            // If empty, show an alert message to the user
            alert('Comment text cannot be empty');
        } else {
            // If not empty, submit the form
            document.getElementById('commentForm').submit();
        }
    }
</script>

<script>
function openEditModal(commentId) {
    // Find the comment container
    var commentContainer = document.querySelector('[data-comment-id="' + commentId + '"]');

    // Find the comment text element
    var commentTextElement = commentContainer.querySelector('.comment-text');

    // Get the current comment text
    var commentText = commentTextElement.textContent;

    // Open a modal for editing
    // You can implement your modal opening logic here, such as showing a hidden modal or creating a new one dynamically
    // For example:
    // showModal(); // Function to show modal

    // Assuming showModal function displays a modal with an input field for editing
    var editedCommentText = showModal(commentText);

    // Update the comment text in the UI
    commentTextElement.textContent = editedCommentText;

    // Show the "(Rediģēts)" label
    var editedLabel = commentContainer.querySelector('.edited-label');
    editedLabel.style.display = 'inline';

    // Send the edited comment text to the server to update the database
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_comment.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Handle the response if needed
        } else {
            // Handle errors
        }
    };
    xhr.onerror = function() {
        // Handle errors
    };
    xhr.send('comment_id=' + encodeURIComponent(commentId) + '&comment_text=' + encodeURIComponent(editedCommentText));
}

</script>

<script>
  // Define a function to show a modal for editing the comment text
function showModal(commentText) {
    // Here, you can implement your modal logic to show a modal with an input field for editing
    // For example, you can create a modal dialog using a library like Bootstrap or create a custom modal

    // For demonstration purposes, let's alert the comment text
    var editedCommentText = prompt("Edit the comment:", commentText);
    return editedCommentText;
}
</script>

<script>
function redirectToProfile(username) {
    // Redirect the user to the profile page based on the username
    window.location.href = 'profile.php?username=' + encodeURIComponent(username);
}
function redirectToSettings(username) {
    // Redirect the user to the profile page based on the username
    window.location.href = 'Iestatijumi/settings.php';
}
function redirectToGames(username) {
    // Redirect the user to the profile page based on the username
    window.location.href = 'Speles/speles.php';
}
</script>

<script>
        function validateForm() {
            var commentText = document.getElementById('commentText').value.trim();
            if (commentText === '') {
                alert('Comment text cannot be empty');
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>

  </main>
</body>
</html>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modal Example</title>
</head>
<body>

<!-- Your HTML content -->
<!-- <div> -->
    <!-- Assuming you have a button for each post with class 'like-btn' and data attribute 'data-post-id' -->
    <!-- <button class="like-btn" data-post-id="1" onclick="handleLike(1)">Like</button>
    <span id="likeCount_1">0</span>
</div> -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attach click event listener to all like buttons
    var likeButtons = document.querySelectorAll('.like-btn');
    likeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Extract the post_id from the data attribute
            var postId = button.getAttribute('data-post-id');
            
            // Send the post_id to the server to handle the like action
            // You can use AJAX here to send a request to your server-side script (like.php)
            // and update the like count for the corresponding post
            fetch('like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ post_id: postId })
            })
            .then(response => response.json())
            .then(data => {
                // Update the like count displayed on the page
                var likeCountElement = document.getElementById('likeCount_' + postId);
                if (likeCountElement) {
                    likeCountElement.textContent = parseInt(likeCountElement.textContent) + (data.action === 'like' ? 1 : -1);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});
</script>


<!-- The modal and overlay elements -->
<div id="myModal" class="modal">
  <input type="text">
  <button id="closeModalBtn">Close Modal</button>
</div>

<div id="overlay" class="overlay"></div>

<script>
  // Get references to modal and overlay elements
  var modal = document.getElementById('myModal');
  var overlay = document.getElementById('overlay');

  // Get references to open and close modal buttons
  var openModalBtn = document.getElementById('openModalBtn');
  var closeModalBtn = document.getElementById('closeModalBtn');

  // Function to open the modal
  function openModal() {
    modal.style.display = 'block';
    overlay.style.display = 'block';
  }

  // Function to close the modal
  function closeModal() {
    modal.style.display = 'none';
    overlay.style.display = 'none';
  }

  // Attach click event listeners to open and close modal buttons
  openModalBtn.addEventListener('click', openModal);
  closeModalBtn.addEventListener('click', closeModal);
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Like Button Example</title>
</head>
<body>

<!-- Sample posts -->
<!-- <div class="post" data-post-id="1">
  <p>Post content...</p>
  <button class="like-btn">Like</button>
  <span class="like-count">0</span>
</div>

<div class="post" data-post-id="2">
  <p>Another post...</p>
  <button class="like-btn">Like</button>
  <span class="like-count">0</span>
</div> -->

<!-- Include jQuery for simplicity -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
  $(document).ready(function() {
    // Event listener for like buttons
    $('.like-btn').click(function() {
      // Get the post ID from the data attribute of the parent post element
      var postId = $(this).closest('.post').data('post-id');

      // Simulate sending a request to the server to update the like count
      $.ajax({
        url: 'like.php',
        method: 'POST',
        data: { postId: postId },
        success: function(response) {
          // Update the like count displayed on the page
          var likeCount = parseInt($('.post[data-post-id="' + postId + '"] .like-count').text());
          $('.post[data-post-id="' + postId + '"] .like-count').text(likeCount + 1);
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
        }
      });
    });
  });
</script>

<!-- JavaScript code -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Attach click event listener to all like buttons
        var likeButtons = document.querySelectorAll('.like-btn');
        likeButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Extract the comment_id from the data attribute
                var commentId = button.getAttribute('data-comment-id');
                
                // Send the comment_id to the server to handle the like action
                // You can use AJAX here to send a request to your server-side script
                // and update the like count for the corresponding comment
                // Example using fetch API:
                fetch('like.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ comment_id: commentId })
                })
                .then(response => response.json())
                .then(data => {
                    // Update the like count displayed on the page
                    var likeCountElement = button.nextElementSibling; // Assuming like count is the next sibling element
                    likeCountElement.textContent = data.like_count;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>

</body>
</html>
