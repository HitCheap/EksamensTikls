var score = 0;
var name = "merkaTreneris";
var restart_button = document.getElementById('restart');
var atpakal_button = document.getElementById('atpakal');
var userScore = document.getElementById('scoreCard');
var block = document.getElementById('block');
var timer = document.getElementById('timer');

var gameTimeInSeconds = 10;
var timerInterval;

// Notikuma klausītājs uz bloka klikšķi, lai spēlētu spēli
document.getElementById('block').addEventListener('click', game);
// Notikuma klausītājs uz restart pogas klikšķi, lai restartētu spēli
document.getElementById('restart').addEventListener('click', restartGame);
// Notikuma klausītājs uz atpakal pogas klikšķi, lai atiestatītu spēli
document.getElementById('atpakal').addEventListener('click', atpakalM);

function game() {
    // Pārbaudiet, vai taimeris nav 00:00
    if (timer.textContent != '00:00') {
        // Palieliniet rezultātu
        score++;
        var blockWidth = block.offsetWidth;
        var blockHeight = block.offsetHeight;
        var windowWidth = window.innerWidth;
        var windowHeight = window.innerHeight;
        
        // Ģenerējiet nejaušus augšējās un kreisās pozīcijas loga robežās
        var top = Math.random() * (windowHeight - blockHeight);
        var left = Math.random() * (windowWidth - blockWidth);

        // Pielāgojiet pozīciju, lai nodrošinātu, ka bloks paliek logā
        if (left + blockWidth > windowWidth) {
            left = windowWidth - blockWidth;
        }
        if (top + blockHeight > windowHeight) {
            top = windowHeight - blockHeight;
        }
        if (left < 0) {
            left = 0;
        }
        if (top < 0) {
            top = 0;
        }

        // Atjauniniet bloka pozīciju
        block.style.top = top + 'px';
        block.style.left = left + 'px';
        
        // Atjauniniet rezultātu displeju
        document.getElementById('scoreCard').textContent = 'Rezultāts: ' + score;

        // Parādīt pogas, kad spēle ir beigusies
        if (timer.textContent === '00:00') {
            restart_button.style.display = 'block';
            atpakal_button.style.display = 'block';
        }
    }
}

function restartGame() {
    // Atiestatīt rezultātu
    score = 0;
    // Slēpt restart un atpakal pogas
    restart_button.style.display = 'none';
    atpakal_button.style.display = 'none';
    // Parādīt bloku
    block.style.display = 'block';
    // Atjaunināt rezultātu displeju
    document.getElementById('scoreCard').textContent = 'Rezultāts: ' + score;

    // Sākt taimeri un spēli
    startTimer();
    game();
}

function atpakalM() {
    // Atiestatīt rezultātu
    score = 0;
    // Slēpt atpakal pogu
    atpakal_button.style.display = 'none';
    // Parādīt bloku
    block.style.display = 'block';
    // Atjaunināt rezultātu displeju
    document.getElementById('scoreCard').textContent = 'Rezultāts: ' + score;
}

function startTimer() {
    // Iestatīt sākotnējo laiku
    var secondsRemaining = gameTimeInSeconds;
    // Atjaunināt taimeri displeju
    timer.textContent = formatTime(secondsRemaining);

    // Sākt atpakaļskaitīšanas taimeri
    timerInterval = setInterval(function() {
        secondsRemaining--;

        if (secondsRemaining >= 0) {
            // Atjaunināt taimeri displeju
            timer.textContent = formatTime(secondsRemaining);
        } else {
            // Pārtraukt taimeri, kad laiks beidzas
            clearInterval(timerInterval);
            // Saglabāt rezultātu
            saveScore(score, name);
            // Beigt spēli
            endGame();
        }
    }, 1000); 
}

function formatTime(seconds) {
    // Formatējiet sekundes mm:ss formātā
    var minutes = Math.floor(seconds / 60);
    var remainingSeconds = seconds % 60;
    return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
}

function endGame() {
    // Parādīt restart un atpakal pogas
    restart_button.style.display = 'block';
    atpakal_button.style.display = 'block';
    // Slēpt bloku
    block.style.display = 'none';
    // Parādīt līderu sarakstu
    showLeaderboard();
}

function saveScore(score, name) {
    // Izveidojiet jaunu XMLHttpRequest objektu
    const xhr = new XMLHttpRequest();
    // Konfigurējiet to: POST pieprasījums uz URL /save_score.php
    xhr.open('POST', 'save_score.php', true);
    // Iestatīt pieprasījuma galveni
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // Nosūtīt pieprasījumu
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Log atbilde
            console.log(xhr.responseText);
        }
    };
    xhr.send('score=' + encodeURIComponent(score) + '&game=' + encodeURIComponent(name));
}

function showLeaderboard() {
    // Izveidojiet jaunu XMLHttpRequest objektu
    const xhr = new XMLHttpRequest();
    // Konfigurējiet to: GET pieprasījums uz URL /leaderboard.php?game=merkaTreneris
    xhr.open('GET', 'leaderboard.php?game=merkaTreneris', true);
    // Nosūtīt pieprasījumu
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Parsēt atbildi
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                const leaderboard = response.leaderboard;
                var leaderboardDisplay = document.getElementById('leaderboard');
                leaderboardDisplay.innerHTML = '<h2>Leaderboard</h2>';
                // Parādīt līderu saraksta ierakstus
                leaderboard.forEach((entry, index) => {
                    leaderboardDisplay.innerHTML += `<p>${index + 1}. Lietotājs ${entry.lietotājvārds}: ${entry.score}</p>`;
                });
            } else {
                console.error('Neizdevās ielādēt līderu sarakstu');
            }
        }
    };
    xhr.send();
}

// Sākt taimeri, kad skripts tiek ielādēts
startTimer();
