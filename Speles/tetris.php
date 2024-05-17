<?php
session_start();

if (!isset($_SESSION['id'])) {
  header('Location: ../Pieslegsanas/login.php');
}
?>

<!DOCTYPE html>  
 <html lang="lv">  
 <head>  
   <meta charset="UTF-8">  
   <meta http-equiv="X-UA-Compatible" content="IE=edge">  
   <meta name="viewport" content="width=device-width, initial-scale=1.0">  
   <title>Tetris</title>  
   <link rel="stylesheet" href="tetris.css">  
 </head>  
 <body>  
  <canvas id="tetris" width="240" height="400"></canvas>
  <canvas id="hold" width="80" height="80"></canvas>
   <div id="score"></div>  
   <script src="tetris.js"></script>  
 </body>  
 </html> 
