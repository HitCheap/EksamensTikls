<?php
session_start();

if (!isset($_SESSION['id'])) {
  header('Location: ../Pieslegsanas/login.php');
}
?>

<!DOCTYPE html>
<html lang="lv">
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="speles.css">
    <title>Spēles</title>
</head>
<body>

<button class="button" onclick="atpakalIndex()">Sākumlapa</button>
<script>
        function atpakalIndex() {
      window.location.href = '../index.php';
    }
    </script>


    <h1>Spēles</h1>

        <a href="merkaTreneris.php">
            <button class="game">
                <img src="../bildes/merkis.png" id="merkisid">
            </button>
        </a>


    <a href="cuska.php">
            <button class="game">
                <img src="../bildes/cuska.png" id="cuskaid">
            </button>
        </a>

        <p class="Mtext">Mērķa Treneris</p>
        <p class="Ctext">Čūska</p>



</body>
</html>
