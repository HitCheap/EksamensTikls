var score = 0;
var name = "merkaTreneris";
var restart_button = document.getElementById('restart');
var atpakal_button = document.getElementById('atpakal');
var userScore = document.getElementById('scoreCard');
var block = document.getElementById('block');
var timer = document.getElementById('timer');

var gameTimeInSeconds = 10;
var timerInterval;

document.getElementById('block').addEventListener('click', game);
document.getElementById('restart').addEventListener('click', restartGame);
document.getElementById('atpakal').addEventListener('click', atpakalM);

function game() {
    if (timer.textContent != '00:00') {
        score++;
        var blockWidth = block.offsetWidth;
        var blockHeight = block.offsetHeight;
        var windowWidth = window.innerWidth;
        var windowHeight = window.innerHeight;
        var top = Math.random() * (windowHeight - blockHeight);
        var left = Math.random() * (windowWidth - blockWidth);

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

        block.style.top = top + 'px';
        block.style.left = left + 'px';
        document.getElementById('scoreCard').textContent = 'Rezultāts: ' + score;

        if (timer.textContent === '00:00') {
            restart_button.style.display = 'block';
            atpakal_button.style.display = 'block';
        }
    }
}

function restartGame() {
    score = 0;
    restart_button.style.display = 'none';
    atpakal_button.style.display = 'none';
    block.style.display = 'block';
    document.getElementById('scoreCard').textContent = 'Rezultāts: ' + score;

    startTimer();
    game();
}

function atpakalM() {
    score = 0;
    atpakal_button.style.display = 'none';
    block.style.display = 'block';
    document.getElementById('scoreCard').textContent = 'Rezultāts: ' + score;
}

function startTimer() {
    var secondsRemaining = gameTimeInSeconds;
    timer.textContent = formatTime(secondsRemaining);

    timerInterval = setInterval(function() {
        secondsRemaining--;

        if (secondsRemaining >= 0) {
            timer.textContent = formatTime(secondsRemaining);
        } else {
            clearInterval(timerInterval);
            saveScore(score, name);
            endGame();
            
        }
    }, 1000); 
}

function formatTime(seconds) {
    var minutes = Math.floor(seconds / 60);
    var remainingSeconds = seconds % 60;
    return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
}

function endGame() {
    restart_button.style.display = 'block';
    atpakal_button.style.display = 'block';
    block.style.display = 'none';
    showLeaderboard();
}

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

function showLeaderboard() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'leaderboard.php?game=merkaTreneris', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                const leaderboard = response.leaderboard;
                xhr.clearRect(0, 0, canvas.width, canvas.height);
                xhr.font = '30px Arial';
                xhr.fillText('Leaderboard', canvas.width / 2 - 90, canvas.height / 2 - 60);
                leaderboard.forEach((entry, index) => {
                    ctx.fillText(`${index + 1}. User ${entry.lietotājvārds}: ${entry.score}`, canvas.width / 2 - 90, canvas.height / 2 - 30 + (index * 30));
                });
            } else {
                console.error('Failed to load leaderboard');
            }
        }
    };
    xhr.send();
}

startTimer();
