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
        // JavaScript code for the T-Rex game

        const canvas = document.getElementById('tRexGame');
        const ctx = canvas.getContext('2d');

        let trex = {
            x: 50,
            y: 150,
            width: 20,
            height: 20,
            speedY: 0,
            gravity: 0.6,
            jump: 10
        };

        let obstacles = [];
        let frame = 0;
        let score = 0;
        let gameOver = false;

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        function drawTrex() {
            ctx.fillStyle = 'black';
            ctx.fillRect(trex.x, trex.y, trex.width, trex.height);
        }

        function updateTrex() {
            trex.y += trex.speedY;
            trex.speedY += trex.gravity;

            if (trex.y + trex.height > canvas.height) {
                trex.y = canvas.height - trex.height;
                trex.speedY = 0;
            }
        }

        function createObstacle() {
            let obstacle = {
                x: canvas.width,
                y: canvas.height - 20,
                width: 20,
                height: 20,
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
                if (trex.x < obstacle.x + obstacle.width &&
                    trex.x + trex.width > obstacle.x &&
                    trex.y < obstacle.y + obstacle.height &&
                    trex.y + trex.height > obstacle.y) {
                    gameOver = true;
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
            if (event.code === 'Space') {
                jump();
            }
        });

        update();
    </script>
</body>
</html>
