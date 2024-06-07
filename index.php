<?php

  session_start();
  include 'navbar.php';
  include 'database.php';
  include 'search_users.php';
  // After successful login
$user_id = $_SESSION['id']; // Retrieve user ID from database or other source
$_SESSION['user_id'] = $user_id; // Store user ID in session variable
  // After successful login
  $_SESSION['lietotaji_id'] = $user_id; // Set the user ID in the session

  if (!isset($_SESSION['id'])) {
    header('Location: Pieslegsanas/login.php');
  }
  


  function attelotKomentarus($parent_id = null, $level = 0) {
    global $conn;
    $currentUserId = isset($_SESSION['id']) ? $_SESSION['id'] : null;

    $sql = "SELECT k.*, l.lietotājvārds, l.profile_picture, l.statuss, COALESCE(k.repost_date, k.datums) as display_date
            FROM (
                SELECT k.*, NULL AS repost_date, 'original' AS source FROM komentari k
                WHERE NOT EXISTS (SELECT 1 FROM reposts r WHERE r.content_id = k.comment_id)
                UNION ALL
                SELECT k.*, r.repost_date, 'repost' AS source FROM komentari k
                INNER JOIN reposts r ON k.comment_id = r.content_id
            ) k
            INNER JOIN lietotaji l ON k.lietotaja_id = l.id
            LEFT JOIN blocked_users b ON k.lietotaja_id = b.blocked_user_id AND b.user_id = ?
            WHERE k.parent_comment_id " . ($parent_id ? "= $parent_id" : "IS NULL") . " AND b.id IS NULL AND l.statuss != 'Deaktivizēts'
            ORDER BY display_date DESC
            LIMIT 10";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $currentUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $commentId = $row['comment_id'];
            $commentText = $row['teksts'];
            $media = $row['media'];
            $source = $row['source'];
            $profilePicture = $row['profile_picture'];
            $statuss = $row['statuss'];

            // Fetch like count from the database
            $likeCountSQL = $conn->prepare("SELECT COUNT(*) AS like_count FROM likes_table WHERE post_id = ?");
            $likeCountSQL->bind_param("i", $commentId);
            $likeCountSQL->execute();
            $likeCountResult = $likeCountSQL->get_result();
            $initialLikeCount = 0;
            if ($likeCountResult) {
                $likeCountData = $likeCountResult->fetch_assoc();
                $initialLikeCount = $likeCountData['like_count'];
            }

            echo '<div class="comment-container" id="comment_' . $commentId . '" onclick="toggleReplies(' . $commentId . ', event)">';
            echo '<div class="profile-info">';
            echo '<img src="' . $profilePicture . '" alt="Profile Picture" class="profile-picture">';
            echo '<p><span class="profile-link" onclick="redirectToProfile(\'' . $row['lietotājvārds'] . '\')">' . $row['lietotājvārds'] . '</span></p>';
            echo '</div>';
            echo '<small class="date">' . date_format(date_create($row['display_date']), "g:i A l, F j, Y") . '</small>';
            echo '<p class="fonts" id="commentText_' . $commentId . '">' . $commentText . '</p>';

            if ($source == 'repost') {
                echo '<small>(Reposted)</small>';
            }

            if ($media) {
                $fileExtension = pathinfo($media, PATHINFO_EXTENSION);
                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $videoExtensions = ['mp4', 'avi', 'mov', 'wmv'];
                $audioExtensions = ['mp3'];

                if (in_array($fileExtension, $imageExtensions)) {
                    echo '<img src="' . $media . '" alt="Uploaded media" class="comment-media">';
                } elseif (in_array($fileExtension, $videoExtensions)) {
                    echo '<video controls class="comment-media">
                            <source src="' . $media . '" type="video/' . $fileExtension . '">
                            Your browser does not support the video tag.
                          </video>';
                } elseif (in_array($fileExtension, $audioExtensions)) {
                    echo '<audio controls class="comment-media">
                            <source src="' . $media . '" type="audio/' . $fileExtension . '">
                            Your browser does not support the audio tag.
                          </audio>';
                } else {
                    echo '<p>Unsupported media type: ' . $fileExtension . '</p>';
                }
            }

            // Like button HTML
echo '<div class="like-container" data-post-id="' . $commentId . '">
    <button id="likeButton_' . $commentId . '" class="like-btn">
        Patīk <span id="likeCount_' . $commentId . '">' . $initialLikeCount . '</span>
    </button>
</div>';


            echo '<button class="comment-btn" onclick="openModal(' . $commentId . ')">Atbildēt</button>';
            echo '<button class="repost-btn" data-content-id="' . $commentId . '">Pārpublicēt</button>';

            if (isset($_SESSION['id']) && $_SESSION['id'] == $row['lietotaja_id'] ) {// || $statuss == 'Administrators' ) {
              echo '<button class="edit-btn" onclick="openEditModal(' . $commentId . ')">Rediģēt</button>';
              echo '<button class="delete-btn" onclick="confirmDelete(' . $commentId . ')">
                    Dzēst
                  </button>';
              if ($row['is_edited']) {
                  echo '<span class="edited-label" title="' . date("Y-m-d H:i:s", strtotime($row['edited_at'])) . '">(Rediģēts)</span>';
              }
            }

            echo '<div class="replies-container" id="replies_' . $commentId . '" style="display: none;">';
            // Display replies
            attelotKomentarus($commentId, $level + 1);
            echo '</div>'; // Close the replies-container

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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <title>Sākumlapa</title>
</head>
<body class="mx-2">
  <main class="main">
    <div class="border">

<!-- Reply Modal -->
<div id="replyModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <form id="replyForm" method="POST" action="reply.php">
      <input type="hidden" name="parent_comment_id" id="replyParentId">
      <textarea name="reply_text" id="replyText" placeholder="Write your reply here..." required></textarea>
      <button type="submit">Submit Reply</button>
    </form>
  </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <form id="editForm" onsubmit="saveEdit(); return false;">
            <input type="hidden" id="editCommentId" name="comment_id">
            <textarea id="editCommentText" name="new_text" required></textarea>
            <button type="submit">Saglabāt</button>
        </form>
    </div>
</div>

<!-- Overlay for the modal -->
<div id="overlay" class="overlay"></div>


    <?php attelotKomentarus(); ?>
  </main>
</body>
</html>



    <?php

  $profileInfo = [
    'lietotājvārds' => $_SESSION['lietotājvārds'],
    'epasts' => $_SESSION['epasts']
  ];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Popup</title>
    <link rel="stylesheet" href="messenger.css">
</head>
<body>
    <button id="chat-button">Messenger</button>
    <div id="chat-window" class="hidden">
        <div id="chat-header">
            <h3>Messenger</h3>
            <button id="close-button">&times;</button>
        </div>
        <div id="chat-body">
            <!-- Chat content goes here -->
            <iframe src="messenger.php" frameborder="0"></iframe>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>

<!-- <!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modal Example</title>
</head>
<body>




 The modal and overlay elements
<div id="myModal" class="modal">
  <input type="text">
  <button id="closeModalBtn">Close Modal</button>
</div>

<div id="overlay" class="overlay"></div>



</body>
</html> -->

<!-- <!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Like Button Example</title>
</head>
<body>

 Sample posts
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



</body>
</html> -->

