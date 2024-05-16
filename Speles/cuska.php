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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cuska.css">
    <script src="cuska.js"></script>
    <title>Čūska</title>
</head>
<body>
    <p id="scoretext">Rezultāts: <span id="score"></span></p>
    <div class="gamecontainer">
        
        <canvas id="board"></canvas>
        <p id="start"></p>
        <div id="end">
            <p id="endscoretext">Rezultāts: <span id="endscore"></span></p>
            <button class="button" onclick="restartet()">Restartēt</buttton>
            <script>
            function restartet(){
            window.location.reload();
            }
            </script>
            <button class="button" onclick="atpakal()">Atpakaļ</buttton>
            <script>
            function atpakal(){
            window.location.href = 'speles.php';
            }
            </script>
        </div> 
        
    </div>
</body>
</html>
