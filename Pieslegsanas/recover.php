<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Account</title>
    <link rel="stylesheet" href="recover.css">
</head>
<body>
    <h1>Atgūt Paroli vai Lietotājvārdu</h1>
    <form action="recover_process.php" method="POST">
        <label for="recover_choice">Izvēlies, ko vēlies atgūt:</label><br>
        <input type="radio" id="recover_password" name="recover_choice" value="password" required>
        <label for="recover_password">Paroli</label><br>
        <input type="radio" id="recover_username" name="recover_choice" value="username" required>
        <label for="recover_username">Lietotājvārdu</label><br>
        <button type="submit">Turpināt</button>
    </form>
</body>
</html>
