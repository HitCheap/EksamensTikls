<?php

  session_start();
  // After successful login
$user_id = $_SESSION['id']; // Retrieve user ID from database or other source
$_SESSION['user_id'] = $user_id; // Store user ID in session variable
  // After successful login
  $_SESSION['lietotaji_id'] = $user_id; // Set the user ID in the session

  if (!isset($_SESSION['id'])) {
    header('Location: login.php');
  }

  $host = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'majaslapa';

  $conn = new mysqli($host, $username, $password, $database);

  function attelotKomentarus() {

    global $conn;

    $sql = "SELECT * FROM komentari ORDER BY datums desc";
    $rezultats = $conn->query($sql);

    if ($rezultats->num_rows > 0) {

      while ($rinda = $rezultats->fetch_assoc()) {

        $sql2 = $conn->prepare("SELECT id, vards, uzvards FROM lietotaji WHERE id = ?");
        $sql2->bind_param('s', $rinda['lietotaja_id']);
        $sql2->execute();

        $rezultats2 = $sql2->get_result();
        $rezultats2 = $rezultats2->fetch_object();

        echo '<div class="border p-5 rounded mb-3">';
        // Modify the onclick function to pass the user ID
        echo '<p class="text-gray-600 font-bold profile-link" onclick="redirectToProfile(\'' . $rezultats2->vards . ' ' . $rezultats2->uzvards . '\')">' . $rezultats2->vards . " " . $rezultats2->uzvards . '</p>';
        echo '<small class="text-xs text-gray-600">' . date_format(date_create($rinda['datums']), "g:i A l, F j, Y") . '</small>';    
        
        echo '<p class="font-semibold">' . $rinda['teksts'] . '</p>';
        echo '<button id="likeButton_1" onclick="handleLike(1)" class="bg-gray-950 text-white px-5 py-1 rounded w-fit font-semibold tracking-wide hover:translate-y-0.5 duration-200 hover:bg-gray-800 text-sm">
        Patīk
        <span class="like-count">0</span>
      </button>';
      echo '<button class="bg-gray-950 text-white px-5 py-1 rounded w-fit font-semibold tracking-wide hover:translate-y-0.5 duration-200 hover:bg-gray-800 text-sm" onclick="openModal(' . $rinda['comment_id'] . ')">Komentēt</button>';
      echo '<button class="bg-gray-950 text-white px-5 py-1 rounded w-fit font-semibold tracking-wide hover:translate-y-0.5 duration-200 hover:bg-gray-800 text-sm">
        Atbildēt
      </button>';
      if (isset($_SESSION['lietotaji_id']) && $_SESSION['lietotaji_id'] == $rinda['lietotaja_id']) {
        echo '<button class="bg-gray-950 text-white px-5 py-1 rounded w-fit font-semibold tracking-wide hover:translate-y-0.5 duration-200 hover:bg-gray-800 text-sm" onclick="confirmDelete(' . $rinda['comment_id'] . ')">
            Dzēst
          </button>';
      }
        echo '</div>';
      }
    } else {
      echo '<p class="italic text-gray-500 text-sm text-center mt-20">Nav pieejamu tvītu.</p>';
    }
  }

    // Assuming you have a likes_table with columns: like_id, post_id, user_id

// Display comments
if ($rezultats->num_rows > 0) {
  while ($rinda = $rezultats->fetch_assoc()) {
      $commentId = $rinda['comment_id'];
      $commentText = $rinda['teksts'];

      // Fetch the number of likes for this comment
      $sqlLikes = "SELECT COUNT(*) AS like_count FROM likes_table WHERE post_id = $commentId";
      $likeResult = $conn->query($sqlLikes);
      $likeCount = ($likeResult->num_rows > 0) ? $likeResult->fetch_assoc()['like_count'] : 0;

      // Output comment HTML
      echo '<div class="comment" id="comment_' . $commentId . '">';
      echo '<p>' . $commentText . '</p>';
      echo '<button onclick="handleLike(' . $commentId . ')">Like (' . $likeCount . ')</button>';
      echo '</div>';
  }
}

?>



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

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <script src="script.js"></script>
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
        <p class="text-lg text-start font-bold">Sveicinati, <?php echo $_SESSION['epasts'] ?>!</p>
        <button onclick="redirectToProfile()">Profils</button>


        <a href="logout.php" class="hover:underline text-sm font-semibold cursor-pointer">Atslēgties</a>
      </div>
      <form action="komentars.php" method="POST" class="mt-3 w-full flex flex-col gap-3">
        <input type="text" name="teksts" placeholder="Raksti komentāru" id="commentText" class="p-3 placeholder:italic border outline-none rounded" oninput="enableButton()"/>
        <div class="text-end">
<button id="submitButton" class="bg-gray-950 text-white px-5 py-1 rounded w-fit font-semibold tracking-wide hover:translate-y-0.5 duration-200 hover:bg-gray-800 text-sm" onclick="validateAndSubmit()" disabled>
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
    header('Location: login.php');
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


<script>
function redirectToProfile(username) {
    // Redirect the user to the profile page based on the username
    window.location.href = 'profile.php?username=' + encodeURIComponent(username);
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

  <!-- Repeat the structure for each post, updating the IDs accordingly -->
<div>
    <button id="likeButton_2" onclick="handleLike(2)">Like</button>
    <span id="likeCount_2">0</span>
</div>

  </main>
</body>
</html>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modal Example</title>
  <style>
    /* Styles for the modal */
    .modal {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
      z-index: 1000;
    }

    /* Styles for the overlay */
    .overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 900;
    }
  </style>
</head>
<body>


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
<div class="post" data-post-id="1">
  <p>Post content...</p>
  <button class="like-btn">Like</button>
  <span class="like-count">0</span>
</div>

<div class="post" data-post-id="2">
  <p>Another post...</p>
  <button class="like-btn">Like</button>
  <span class="like-count">0</span>
</div>

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

<div class="comment">
    <p><?php echo $rinda; ?></p>
    <button class="like-btn" data-comment-id="<?php echo $comment_id; ?>">Like</button>
    <span class="like-count">0</span>
</div>


<h1>NEW CODE</h1>

<!-- HTML code for displaying comments -->
<div class="comment">
    <p>Comment text...</p>
    <button class="like-btn" data-comment-id="1">Like</button>
    <span class="like-count">0</span>
</div>


<div class="comment">
    <p>Another comment text...</p>
    <button class="like-btn" data-comment-id="2">Like</button>
    <span class="like-count">0</span>
</div>

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