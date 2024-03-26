<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Mājaslapa</title>
</head>
<body class="mx-2">
  <main class="main">
    <div class="border">
      <div class="items">
        
        <button class="button" onclick="atpakalIndex()">Atpakal</button>
        <a href="logout.php" class="logout">Atslēgties</a>
      </div>
    </div>
    <script>
        function atpakalIndex() {
      window.location.href = 'index.php';
    }
    </script>
  </main>
</body>
</html>
