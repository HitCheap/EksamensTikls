var blockSize = 30;  // Bloka izmērs
var rows = 17;  // Rindu skaits
var columns = 38;  // Kolonnu skaits
var board;  // Spēles laukums
var context;  // Konteksts zīmēšanai

var snakeX = blockSize * 5;  // Čūskas sākuma pozīcija X koordinātē
var snakeY = blockSize * 5;  // Čūskas sākuma pozīcija Y koordinātē

var speedX = 0;  // Čūskas kustības ātrums X koordinātē
var speedY = 0;  // Čūskas kustības ātrums Y koordinātē

var snakeBody = [];  // Čūskas ķermenis

var foodX;  // Pārtikas X koordināte
var foodY;  // Pārtikas Y koordināte

var score = 0;  // Punktu skaits
var name = "cuska";  // Spēles nosaukums

var gameOver = false;  // Spēles beigu stāvoklis

var endscreen;  // Beigu ekrāns

window.onload = function () {
    // Iestatīt laukuma augstumu un platumu
    board = document.getElementById("board");
    board.height = rows * blockSize;
    board.width = columns * blockSize;
    context = board.getContext("2d");

    placeFood();  // Novietot pārtiku
    showScore();  // Parādīt punktu skaitu
    document.addEventListener("keyup", changeDirection);  // Pievienot klausītāju kustībām
    // Iestatīt čūskas ātrumu
    setInterval(update, 1000 / 8);
    end = document.getElementById("end");
    end.style.display = "none";  // Sākumā slēpt beigu ekrānu
}

function update() {
    if (gameOver) {
        end.style.display = "block";  // Parādīt beigu ekrānu
        document.getElementById("endscore").innerHTML = score;  // Parādīt beigu punktu skaitu
        document.getElementById("score").style.display = "none";  // Slēpt punktu skaitītāju
        document.getElementById("scoretext").style.display = "none";  // Slēpt punktu tekstu
        return;
    }

    showScore();  // Parādīt punktu skaitu
    // Spēles laukuma fons
    context.fillStyle = "#262626";
    context.fillRect(0, 0, board.width, board.height);

    // Pārtikas krāsa un pozīcija
    context.fillStyle = "red";
    context.fillRect(foodX, foodY, blockSize, blockSize);

    // Ja čūska apēd pārtiku
    if (snakeX == foodX && snakeY == foodY) {
        snakeBody.push([foodX, foodY]);
        placeFood();  // Novietot jaunu pārtiku
        score++;  // Palielināt punktu skaitu
    }

    // Čūskas ķermenis pieaug
    for (let i = snakeBody.length - 1; i > 0; i--) {
        // Saglabāt iepriekšējo ķermeņa daļu nākamajā
        snakeBody[i] = snakeBody[i - 1];
    }
    if (snakeBody.length) {
        snakeBody[0] = [snakeX, snakeY];
    }

    // Zīmēt čūsku
    context.fillStyle = "white";
    snakeX += speedX * blockSize;  // Atjaunināt čūskas pozīciju X koordinātē
    snakeY += speedY * blockSize;  // Atjaunināt čūskas pozīciju Y koordinātē
    context.fillRect(snakeX, snakeY, blockSize, blockSize);
    for (let i = 0; i < snakeBody.length; i++) {
        context.fillRect(snakeBody[i][0], snakeBody[i][1], blockSize, blockSize);
    }

    // Pārbaudīt robežas
    if (snakeX < 0 || snakeX >= columns * blockSize || snakeY < 0 || snakeY >= rows * blockSize) {
        gameOver = true;  // Spēle beidzas
        saveScore(score, name);  // Saglabāt punktu skaitu
    }

    // Pārbaudīt, vai čūska ēd savu ķermeni
    for (let i = 0; i < snakeBody.length; i++) {
        if (snakeX == snakeBody[i][0] && snakeY == snakeBody[i][1]) {
            gameOver = true;  // Spēle beidzas
            saveScore(score, name);  // Saglabāt punktu skaitu
        }
    }
}

// Čūskas kustība
function changeDirection(e) {
    if (e.code == "ArrowUp" && speedY != 1) {
        // Ja tiek nospiesta augšupvērstā bultiņa un čūska nepārvietojas uz leju
        speedX = 0;
        speedY = -1;
    } else if (e.code == "ArrowDown" && speedY != -1) {
        // Ja tiek nospiesta lejupvērstā bultiņa
        speedX = 0;
        speedY = 1;
    } else if (e.code == "ArrowLeft" && speedX != 1) {
        // Ja tiek nospiesta kreisā bultiņa
        speedX = -1;
        speedY = 0;
    } else if (e.code == "ArrowRight" && speedX != -1) {
        // Ja tiek nospiesta labā bultiņa
        speedX = 1;
        speedY = 0;
    }
}

// Pārtikas novietošana nejaušās vietās
function placeFood() {
    // X koordinātē
    foodX = Math.floor(Math.random() * columns) * blockSize;
    // Y koordinātē
    foodY = Math.floor(Math.random() * rows) * blockSize;
}

// Parādīt punktu skaitu
function showScore() {
    document.getElementById("score").innerHTML = score;
}

// Saglabāt punktu skaitu
function saveScore(score, name) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_score.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
        }
    };
    xhr.send('score=' + encodeURIComponent(score) + '&game=' + encodeURIComponent(name));
}
