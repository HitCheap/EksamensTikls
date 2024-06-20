<?php

session_start(); // Uzsāk sesiju
include 'navbar.php'; // Iekļauj navigācijas joslas failu
include 'datubaze.php'; // Iekļauj datubāzes savienojuma failu
include 'search_users.php'; // Iekļauj lietotāju meklēšanas funkciju failu

// Pēc veiksmīgas pieteikšanās
$user_id = $_SESSION['id']; // Paņem lietotāja ID no sesijas
$_SESSION['user_id'] = $user_id; // Saglabā lietotāja ID sesijas mainīgajā
$_SESSION['lietotaji_id'] = $user_id; // Iestata lietotāja ID sesijā

// Pārbauda, vai lietotājs ir pieteicies
if (!isset($_SESSION['id'])) {
  header('Location: login.php'); // Ja lietotājs nav pieteicies, novirza uz pieteikšanās lapu
}

// Funkcija, lai attēlotu komentārus
function attelotKomentarus($parent_id = null, $level = 0) {
    global $conn; // Izmanto globālo datubāzes savienojuma mainīgo
    $currentUserId = isset($_SESSION['id']) ? $_SESSION['id'] : null; // Paņem pašreizējā lietotāja ID no sesijas

    // SQL vaicājums, lai iegūtu komentārus
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

    $stmt = $conn->prepare($sql); // Sagatavo SQL vaicājumu
    $stmt->bind_param('i', $currentUserId); // Piesaista pašreizējā lietotāja ID parametram
    $stmt->execute(); // Izpilda SQL vaicājumu
    $result = $stmt->get_result(); // Iegūst rezultātu

    // Pārbauda, vai ir rezultāti
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) { // Iterē caur katru rezultātu rindu
            $commentId = $row['comment_id']; // Paņem komentāra ID
            $commentText = $row['teksts']; // Paņem komentāra tekstu
            $media = $row['media']; // Paņem komentāra mediju failu
            $source = $row['source']; // Paņem komentāra avotu (oriģināls vai pārpublicēts)
            $profilePicture = $row['profile_picture']; // Paņem lietotāja profila attēlu
            $statuss = $row['statuss']; // Paņem lietotāja statusu
            $liked = false;
            // Iegūst laiku skaitu no datubāzes
            $likeCountSQL = $conn->prepare("SELECT COUNT(*) AS like_count FROM likes_table WHERE post_id = ?");
            $likeCountSQL->bind_param("i", $commentId); // Piesaista komentāra ID parametram
            $likeCountSQL->execute(); // Izpilda SQL vaicājumu
            $likeCountResult = $likeCountSQL->get_result(); // Iegūst rezultātu
            $initialLikeCount = 0; // Inicializē laiku skaitu
            if ($likeCountResult) {
                $likeCountData = $likeCountResult->fetch_assoc(); // Iegūst laiku skaita datus
                $initialLikeCount = $likeCountData['like_count']; // Iestata sākotnējo laiku skaitu
            }

            if (isset($_SESSION['id'])) {
              $checkLikeSQL = $conn->prepare("SELECT * FROM likes_table WHERE user_id = ? AND post_id = ?");
              $checkLikeSQL->bind_param("ii", $_SESSION['id'], $commentId);
              $checkLikeSQL->execute();
              $checkLikeResult = $checkLikeSQL->get_result();
              $liked = $checkLikeResult->num_rows > 0;
          }
      

            // Nosaka profila attēla ceļu
            if ($profilePicture === 'bildes/default.png') {
                $profilePicturePath = './' . $profilePicture;
            } else {
                $profilePicturePath = './profile_pictures/' . $profilePicture;
            }

            // Pārbauda, vai profila attēla fails eksistē vai nav tukšs
            if (!file_exists($profilePicturePath) || empty($profilePicture)) {
                $profilePicturePath = './bildes/default.png'; // Iestata noklusējuma attēlu, ja fails neeksistē vai ir tukšs
            }

            // Izvada komentāra HTML
            echo '<div class="comment-container" id="comment_' . $commentId . '" onclick="toggleReplies(' . $commentId . ', event)">';
            echo '<div class="profile-info">';
            echo '<img src="' . htmlspecialchars($profilePicturePath) . '" alt="Profile Picture" class="profile-picture">';
            echo '<p><span class="profile-link" onclick="redirectToProfile(\'' . $row['lietotājvārds'] . '\')">' . $row['lietotājvārds'] . '</span></p>';
            echo '</div>';
            echo '<small class="date">' . date_format(date_create($row['display_date']), "g:i A l, F j, Y") . '</small>';
            echo '<p class="fonts" id="commentText_' . $commentId . '">' . $commentText . '</p>';

            // Ja komentārs ir pārpublicēts, pievieno "Reposted" norādi
            if ($source == 'repost') {
                echo '<small>(Reposted)</small>';
            }

            // Attēlo mediju failu, ja tas eksistē
            if ($media) {
                $fileExtension = pathinfo($media, PATHINFO_EXTENSION); // Nosaka faila paplašinājumu
                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Attēlu paplašinājumi
                $videoExtensions = ['mp4', 'avi', 'mov', 'wmv']; // Video paplašinājumi
                $audioExtensions = ['mp3']; // Audio paplašinājumi

                // Attēlo atbilstošu mediju tipu
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

            // Laiku pogas HTML
            echo '<div class="like-container" data-post-id="' . $commentId . '">
                <button id="likeButton_' . $commentId . '" class="like-btn">
                    Patīk <span id="likeCount_' . $commentId . '">' . $initialLikeCount . '</span>
                </button>
            </div>';

            echo '<button class="comment-btn" onclick="openModal(' . $commentId . ')">Atbildēt</button>'; // Atbildes poga
            echo '<button class="repost-btn" data-content-id="' . $commentId . '">Pārpublicēt</button>'; // Pārpublicēšanas poga

            // Ja lietotājs ir komentāra autors vai administrators, pievieno rediģēšanas un dzēšanas pogas
            if (isset($_SESSION['id']) && $_SESSION['id'] == $row['lietotaja_id']) {
                echo '<button class="edit-btn" onclick="openEditModal(' . $commentId . ')">Rediģēt</button>';
                echo '<button class="delete-btn" onclick="confirmDelete(' . $commentId . ')">Dzēst</button>';
                if ($row['is_edited']) {
                    echo '<span class="edited-label" title="' . date("Y-m-d H:i:s", strtotime($row['edited_at'])) . '">(Rediģēts)</span>';
                }
            }

            // Attēlo atbildes uz komentāru
            echo '<div class="replies-container" id="replies_' . $commentId . '" style="display: none;">';
            attelotKomentarus($commentId, $level + 1); // Rekursīvi attēlo atbildes
            echo '</div>'; // Aizver atbilžu konteineru

            echo '</div>'; // Aizver komentāra konteineru
        }
    } else {
        echo '<p class="nav">Nav atbildes priekš šī komentāra.</p>'; // Ja nav atbilžu, attēlo ziņojumu
    }

    $stmt->close(); // Aizver SQL vaicājuma sagatavošanu
}

// Funkcija, lai pārbaudītu, vai lietotājs ir bloķēts
function isBlocked($currentUserId, $commenterId) {
    global $conn; // Izmanto globālo datubāzes savienojuma mainīgo

    // SQL vaicājums, lai pārbaudītu, vai komentētājs ir bloķēts
    $sql = "SELECT COUNT(*) AS blocked 
            FROM blocked_users 
            WHERE user_id = ? AND blocked_user_id = ?";

    $stmt = $conn->prepare($sql); // Sagatavo SQL vaicājumu
    $stmt->bind_param("ii", $currentUserId, $commenterId); // Piesaista lietotāja ID un bloķētā lietotāja ID parametram
    $stmt->execute(); // Izpilda SQL vaicājumu
    $result = $stmt->get_result(); // Iegūst rezultātu
    $row = $result->fetch_assoc(); // Iegūst rezultātu rindu

    return $row['blocked'] > 0; // Atgriež true, ja komentētājs ir bloķēts, pretējā gadījumā false
}

// Izvada komentārus

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

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css"> <!-- Saite uz stila lapu -->
  <script src="script.js"></script> <!-- Saite uz JavaScript failu -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!-- JQuery bibliotēka -->
  <title>Sākumlapa</title>
  <style>
    /* Add a margin-top to the comment container to make room for the navbar */
   .comment-container {
        margin-top: 20px; /* adjust the value to match the height of your navbar */
    }
</style>
</head>
<body class="mx-2">
  <main class="main">
    <div class="border">

<!-- Atbildes modālais logs -->
<div id="replyModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span> <!-- Poga, lai aizvērtu modālo logu -->
    <form id="replyForm" method="POST" action="reply.php"> <!-- Forma, lai iesniegtu atbildi -->
      <input type="hidden" name="parent_comment_id" id="replyParentId">
      <textarea name="reply_text" id="replyText" placeholder="Write your reply here..." required></textarea>
      <button type="submit">Submit Reply</button>
    </form>
  </div>
</div>

<!-- Rediģēšanas modālais logs -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span> <!-- Poga, lai aizvērtu rediģēšanas logu -->
        <form id="editForm" onsubmit="saveEdit(); return false;"> <!-- Forma, lai saglabātu rediģēto komentāru -->
            <input type="hidden" id="editCommentId" name="comment_id">
            <textarea id="editCommentText" name="new_text" required></textarea>
            <button type="submit">Saglabāt</button>
        </form>
    </div>
</div>

<!-- Pārklājums modālajiem logiem -->
<div id="overlay" class="overlay"></div>

<?php attelotKomentarus(); // Attēlo komentārus ?>
  </main>
</body>
</html>

<?php

$profileInfo = [
  'lietotājvārds' => $_SESSION['lietotājvārds'], // Paņem lietotājvārdu no sesijas
  'epasts' => $_SESSION['epasts'] // Paņem e-pastu no sesijas
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat Popup</title>
</head>
<body>
  <button id="chat-button">Čats</button> <!-- Poga, lai atvērtu čata logu -->
  <div id="chat-window" class="hidden">
    <div id="chat-header">
      <h3>Čats</h3>
      <button id="close-button">&times;</button> <!-- Poga, lai aizvērtu čata logu -->
    </div>
    <div id="chat-body">
      <!-- Čata saturs -->
      <iframe src="messenger.php" frameborder="0"></iframe> <!-- Iframe, lai ielādētu čata saturu -->
    </div>
  </div>

  <script src="script.js"></script> <!-- Saite uz JavaScript failu -->
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

