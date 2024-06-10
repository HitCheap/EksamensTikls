<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T-Rex Game</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }
        canvas {
            display: block;
            background: #f7f7f7;
        }
    </style>
</head>
<body>
    <canvas id="tRexGame"></canvas>
    <script>
        const canvas = document.getElementById('tRexGame');
        const ctx = canvas.getContext('2d');

        let trex = {
            x: 50,
            y: 150,
            width: 20,
            height: 40,  // Updated height
            speedY: 0,
            gravity: 0.6,
            jump: 10,
            ducking: false
        };

        let obstacles = [];
        let frame = 0;
        let score = 0;
        let name = "trex";
        let gameOver = false;

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        function drawTrex() {
    ctx.fillStyle = 'black';
    if (trex.ducking) {
        // Draw the wider and shorter dinosaur at ground level when ducking
        ctx.fillRect(trex.x, canvas.height - trex.height / 2, trex.width * 1.5, trex.height / 2);
    } else {
        // Draw the regular-sized dinosaur
        ctx.fillRect(trex.x, trex.y, trex.width, trex.height);
    }
}




        function updateTrex() {
            if (!trex.ducking) {
                trex.y += trex.speedY;
                trex.speedY += trex.gravity;

                if (trex.y + trex.height > canvas.height) {
                    trex.y = canvas.height - trex.height;
                    trex.speedY = 0;
                }
            }
        }

        function createObstacle() {
    let height = 20;
    let y = canvas.height - height;
    if (Math.random() > 0.8) {  // 20% chance to spawn flying obstacle
        y -= trex.height + height;
        height *= 3; // Make the flying blocks three blocks high
    } else {
        if (Math.random() > 0.5) { // 50% chance to make lower ground blocks 2 blocks tall
            height *= 2;
        }
    }
    
    let obstacle = {
        x: canvas.width,
        y: y,
        width: 20,
        height: height,
        speedX: 5
    };
    obstacles.push(obstacle);
}




        function drawObstacles() {
            ctx.fillStyle = 'red';
            obstacles.forEach(obstacle => {
                ctx.fillRect(obstacle.x, obstacle.y, obstacle.width, obstacle.height);
            });
        }

        function updateObstacles() {
            obstacles.forEach(obstacle => {
                obstacle.x -= obstacle.speedX;
            });

            obstacles = obstacles.filter(obstacle => obstacle.x + obstacle.width > 0);
        }

        function checkCollision() {
    obstacles.forEach(obstacle => {
        let trexHeight = trex.ducking ? trex.height / 2 : trex.height; // Adjusted height when ducking
        let trexY = trex.ducking ? canvas.height - trex.height / 2 : trex.y; // Adjusted Y position when ducking
        if (trex.x < obstacle.x + obstacle.width &&
            trex.x + trex.width > obstacle.x &&
            trexY < obstacle.y + obstacle.height &&
            trexY + trexHeight > obstacle.y) {
            gameOver = true;
            saveScore(score, name);
            showLeaderboard();
        }
    });
}


        function jump() {
            if (trex.y + trex.height >= canvas.height) {
                trex.speedY = -trex.jump;
            }
        }

        function update() {
            if (!gameOver) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                drawTrex();
                updateTrex();

                if (frame % 60 === 0) {
                    createObstacle();
                }

                drawObstacles();
                updateObstacles();
                checkCollision();

                score++;
                frame++;
            } else {
                ctx.font = '30px Arial';
                ctx.fillText('Game Over', canvas.width / 2 - 70, canvas.height / 2);
                ctx.fillText('Score: ' + score, canvas.width / 2 - 70, canvas.height / 2 + 40);
            }

            requestAnimationFrame(update);
        }

        window.addEventListener('keydown', function(event) {
            if (event.code === 'ArrowUp') {
                jump();
            } else if (event.code === 'ArrowDown') {
                trex.ducking = true;
            }
        });

        window.addEventListener('keyup', function(event) {
            if (event.code === 'ArrowDown') {
                trex.ducking = false;
            }
        });

        update();

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
            xhr.open('GET', 'leaderboard.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const leaderboard = JSON.parse(xhr.responseText);
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.font = '30px Arial';
                    ctx.fillText('Leaderboard', canvas.width / 2 - 90, canvas.height / 2 - 60);
                    leaderboard.forEach((entry, index) => {
                        ctx.fillText(`${index + 1}. ${entry.name}: ${entry.score}`, canvas.width / 2 - 90, canvas.height / 2 - 30 + (index * 30));
                    });
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
