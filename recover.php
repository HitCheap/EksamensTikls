<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atgūt Paroli vai Lietotājvārdu</title>
    <link rel="stylesheet" href="atgut.css">
</head>
<body>
    <h1>Atgūt Paroli vai Lietotājvārdu</h1>
    <form action="recover_process.php" method="POST">
        <label for="recover_choice">Izvēlies, ko vēlies atgūt:</label>
        <div>
            <label class="option" for="recover_password">
                <input type="radio" id="recover_password" name="recover_choice" value="password" required>
                Paroli
            </label>
        </div>
        <div>
            <label class="option" for="recover_username">
                <input type="radio" id="recover_username" name="recover_choice" value="username" required>
                Lietotājvārdu
            </label>
        </div>
        <button type="submit">Turpināt</button>
        <button onclick="atpakal()">Atpakaļ</button>
    </form>

    <script>
         function atpakal() {
            window.location.href = 'login.php';
        }

        const options = document.querySelectorAll('.option');
        options.forEach((option) => {
            option.addEventListener('click', () => {
                options.forEach((otherOption) => {
                    otherOption.classList.remove('selected');
                });
                option.classList.add('selected');
            });
        });
    </script>
</body>
</html>