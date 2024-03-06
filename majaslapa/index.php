<?php

  session_start();

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

        $sql2 = $conn->prepare("SELECT vards, uzvards FROM lietotaji WHERE id = ?");
        $sql2->bind_param('s', $rinda['lietotaja_id']);
        $sql2->execute();

        $rezultats2 = $sql2->get_result();
        $rezultats2 = $rezultats2->fetch_object();

        echo '<div class="border p-5 rounded mb-3">';
        echo '<p class="text-gray-600 font-bold">' . $rezultats2->vards . " " . $rezultats2->uzvards . '</p>';
        echo '<small class="text-xs text-gray-600">' . date_format(date_create($rinda['datums']), "g:i A l, F j, Y") . '</small>';
        echo '<p class="font-semibold">' . $rinda['teksts'] . '</p>';
        echo '<button class="bg-gray-950 text-white px-5 py-1 rounded w-fit font-semibold tracking-wide hover:translate-y-0.5 duration-200 hover:bg-gray-800 text-sm">
        Like
      </button>';
      echo '<button class="bg-gray-950 text-white px-5 py-1 rounded w-fit font-semibold tracking-wide hover:translate-y-0.5 duration-200 hover:bg-gray-800 text-sm">
        Comment
      </button>';
      echo '<button class="bg-gray-950 text-white px-5 py-1 rounded w-fit font-semibold tracking-wide hover:translate-y-0.5 duration-200 hover:bg-gray-800 text-sm">
        Reply
      </button>';
      echo '<button class="bg-gray-950 text-white px-5 py-1 rounded w-fit font-semibold tracking-wide hover:translate-y-0.5 duration-200 hover:bg-gray-800 text-sm">
        Dzēst
      </button>';
        echo '</div>';
      }
    } else {
      echo '<p class="italic text-gray-500 text-sm text-center mt-20">Nav pieejamu tvītu.</p>';
    }
  }

?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <p class="text-lg text-start font-bold">Sveicinati, <?php echo $_SESSION['vards'] ?>!</p>
        <button onclick="openProfileTab()">Profils</button>
        <a href="logout.php" class="hover:underline text-sm font-semibold cursor-pointer">Atslēgties</a>
      </div>
      <form action="komentars.php" method="POST" class="mt-3 w-full flex flex-col gap-3">
        <input type="text" name="teksts" placeholder="Raksti komentāru" class="p-3 placeholder:italic border outline-none rounded"/>
        <div class="text-end">
          <button class="bg-gray-950 text-white px-5 py-1 rounded w-fit font-semibold tracking-wide hover:translate-y-0.5 duration-200 hover:bg-gray-800 text-sm">
            Publicēt
          </button>
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
    function openProfileTab() {
      window.location.href = 'profile.php';
    }
  </script>
  </main>
</body>
</html>

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



