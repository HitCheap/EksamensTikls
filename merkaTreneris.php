<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="merkaTreneris.css">
    <title>Mērķa Treneris</title>
</head>
<body>
    <button id="block"></button>
    <button id="restart">Restartēt</button>
    <button id="atpakal" onclick="atpakalIndex()">Atpakaļ</button>

    <script>
        function atpakalIndex() {
            window.location.href = 'speles.php';
        }
    </script>

    <div id="timer">Laiks: </div>
    <div id="scoreCard">Rezultāts: 0</div>

    <script src="merkaTreneris.js"></script>
</body>
</html>
