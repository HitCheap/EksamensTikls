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

    <style>
    .hidden-link {
        display: none;
    }
</style>

        <a href="merkaTreneris.php">
            <button class="game">
                <img src="../bildes/merkis.png" id="attelsid">
            </button>
        </a>


        <a href="cuska.php">
            <button class="game">
                <img src="../bildes/cuska.png" id="attelsid">
            </button>
        </a>

        <a href="tetris.php">
            <button class="game">
                <img src="../bildes/tetris.png" id="attelsid">
            </button>
        </a>

        <a href="t-rex.php">
        <button class="game">
            <img src="../bildes/t-rex.png" id="attelsid">
        </button>
        </a>

        <p class="Mtext">Mērķa Treneris</p>
        <p class="Ctext">Čūska</p>
        <p class="Ttext">Tetris</p>
        <p class="TRtext">T-Rex Game</p>


</body>
</html>
