"use strict";
const canvas = document.getElementById("tetris");
const context = canvas.getContext("2d");
context.scale(20, 20);

const holdCanvas = document.getElementById("hold");
const holdContext = holdCanvas.getContext("2d");
holdContext.scale(20, 20);

let score = 0;
let game = 'tetris';

function arenaSweep() {
  let rowCount = 1;
  outer: for (let y = arena.length - 1; y > 0; --y) {
    for (let x = 0; x < arena[y].length; ++x) {
      if (arena[y][x] === 0) {
        continue outer;
      }
    }
    const row = arena.splice(y, 1)[0].fill(0);
    arena.unshift(row);
    ++y;
    player.score += rowCount * 10;
    rowCount *= 2;
  }
}

function collide(arena, player) {
  const m = player.matrix;
  const o = player.pos;
  for (let y = 0; y < m.length; ++y) {
    for (let x = 0; x < m[y].length; ++x) {
      if (m[y][x] !== 0 && (arena[y + o.y] && arena[y + o.y][x + o.x]) !== 0) {
        return true;
      }
    }
  }
  return false;
}

function createMatrix(w, h) {
  const matrix = [];
  while (h--) {
    matrix.push(new Array(w).fill(0));
  }
  return matrix;
}

function createPiece(type) {
  if (type === "I") {
    return [
      [0, 1, 0, 0],
      [0, 1, 0, 0],
      [0, 1, 0, 0],
      [0, 1, 0, 0],
    ];
  } else if (type === "L") {
    return [
      [0, 2, 0],
      [0, 2, 0],
      [0, 2, 2],
    ];
  } else if (type === "J") {
    return [
      [0, 3, 0],
      [0, 3, 0],
      [3, 3, 0],
    ];
  } else if (type === "O") {
    return [
      [4, 4],
      [4, 4],
    ];
  } else if (type === "Z") {
    return [
      [5, 5, 0],
      [0, 5, 5],
      [0, 0, 0],
    ];
  } else if (type === "S") {
    return [
      [0, 6, 6],
      [6, 6, 0],
      [0, 0, 0],
    ];
  } else if (type === "T") {
    return [
      [0, 7, 0],
      [7, 7, 7],
      [0, 0, 0],
    ];
  }
}

function drawMatrix(matrix, offset, context, color = null) {
  matrix.forEach((row, y) => {
    row.forEach((value, x) => {
      if (value !== 0) {
        context.fillStyle = color ? `rgba(${color.join(',')}, 0.5)` : colors[value];
        context.fillRect(x + offset.x, y + offset.y, 1, 1);
      }
    });
  });
}

function draw() {
  context.fillStyle = '#000';
  context.fillRect(0, 0, canvas.width, canvas.height);

  // Draw the silhouette
  const silhouettePos = calculateSilhouette();
  drawMatrix(player.matrix, silhouettePos, context, [255, 255, 255]);

  // Draw the arena
  drawMatrix(arena, { x: 0, y: 0 }, context);

  // Draw the actual piece
  drawMatrix(player.matrix, player.pos, context);

  // Draw the held piece
  holdContext.clearRect(0, 0, holdCanvas.width, holdCanvas.height);
  if (holdPiece) {
    const offset = { x: 1, y: 1 }; // Adjust the offset for a better visual position
    drawMatrix(holdPiece, offset, holdContext);
  }
}

let gameOverFlag = false;

function update(time = 0) {
  if (!gameOverFlag) {
    const deltaTime = time - lastTime;
    dropCounter += deltaTime;
    if (dropCounter > dropInterval) {
      playerDrop();
    }
    lastTime = time;

    draw();
    requestAnimationFrame(update);
  }
}

function merge(arena, player) {
  player.matrix.forEach((row, y) => {
    row.forEach((value, x) => {
      if (value !== 0) {
        arena[y + player.pos.y][x + player.pos.x] = value;
      }
    });
  });
}

function rotate(matrix, dir) {
  for (let y = 0; y < matrix.length; ++y) {
    for (let x = 0; x < y; ++x) {
      [matrix[x][y], matrix[y][x]] = [matrix[y][x], matrix[x][y]];
    }
  }
  if (dir > 0) {
    matrix.forEach((row) => row.reverse());
  } else {
    matrix.reverse();
  }
}

function playerDrop() {
  player.pos.y++;
  if (collide(arena, player)) {
    player.pos.y--;
    merge(arena, player);
    playerReset();
    arenaSweep();
    updateScore();
    if (isGameOver()) {
      gameOver();
    }
  }
  dropCounter = 0;
}

function isGameOver() {
  for (let x = 0; x < arena[0].length; x++) {
    if (arena[0][x] !== 0) {
      return true;
    }
  }
  return false;
}

function gameOver() {
  gameOverFlag = true;
  // Display game over screen with restart and back buttons
  const gameOverScreen = document.getElementById('game-over-screen');
  gameOverScreen.style.display = 'flex';
  gameOverScreen.innerHTML = `
    <div class="game-over-content">
      <p>Game Over!</p>
      <p>Score: ${player.score}</p>
      <button onclick="restartGame()">Restart</button>
      <button onclick="goBack()">Back</button>
    </div>
  `;
  saveScore(player.score, game); // Save the score to the server
}

function playerMove(offset) {
  player.pos.x += offset;
  if (collide(arena, player)) {
    player.pos.x -= offset;
  }
}

function playerReset() {
  const pieces = "TJLOSZI";
  player.matrix = createPiece(pieces[(pieces.length * Math.random()) | 0]);
  player.pos.y = 0;
  player.pos.x =
    ((arena[0].length / 2) | 0) - ((player.matrix[0].length / 2) | 0);
  if (collide(arena, player)) {
    gameOver();
  }
  hasPlacedPiece = true; // Reset the flag to allow holding a piece
}

function playerRotate(dir) {
  const pos = player.pos.x;
  let offset = 1;
  rotate(player.matrix, dir);
  while (collide(arena, player)) {
    player.pos.x += offset;
    offset = -(offset + (offset > 0 ? 1 : -1));
    if (offset > player.matrix[0].length) {
      rotate(player.matrix, -dir);
      player.pos.x = pos;
      return;
    }
  }
}

let dropCounter = 0;
let dropInterval = 1000;
let lastTime = 0;

let holdPiece = null; // Variable to store the hold piece
let hasHeldPiece = false; // Variable to track if a piece has been held previously
let hasPlacedPiece = true; // Variable to track if a piece has been placed after holding

// Function to hold the current piece
function holdCurrentPiece() {
  if (hasPlacedPiece) {
    if (holdPiece) {
      const temp = holdPiece;
      holdPiece = player.matrix;
      player.matrix = temp;
      player.pos.y = 0;
      player.pos.x = ((arena[0].length / 2) | 0) - ((player.matrix[0].length / 2) | 0);
    } else {
      holdPiece = player.matrix;
      playerReset();
    }
    hasHeldPiece = true;
    hasPlacedPiece = false;
  }
}

// Add this function to calculate the silhouette position
function calculateSilhouette() {
  const originalPos = { ...player.pos };
  while (!collide(arena, player)) {
    player.pos.y++;
  }
  player.pos.y--;
  const silhouettePos = { x: player.pos.x, y: player.pos.y };

  player.pos = originalPos;

  return silhouettePos;
}

function updateScore() {
  document.getElementById("score").innerText = "Score: " + player.score;
}

document.addEventListener("keydown", (event) => {
  if (event.keyCode === 37) {
    playerMove(-1);
  } else if (event.keyCode === 39) {
    playerMove(1);
  } else if (event.keyCode === 40) {
    playerDrop();
  } else if (event.keyCode === 38) {
    playerRotate(1);
  } else if (event.keyCode === 67) { // 'c' button keycode
    holdCurrentPiece();
  } else if (event.keyCode === 32) {
    while (!collide(arena, player)) {
      player.pos.y++;
    }
    player.pos.y--;
    merge(arena, player);
    playerReset();
    arenaSweep();
    updateScore();
  }
});

const colors = [
  null,
  "#00ffff",
  "#ff7f00",
  "#0000ff",
  "#ffff00",
  "#ff0000",
  "#00ff00",
  "#800080",
];

const arena = createMatrix(12, 20);
const player = {
  pos: { x: 0, y: 0 },
  matrix: null,
  score: 0,
};

playerReset();
updateScore();
update();

function saveScore(score, name) {
  const xhr = new XMLHttpRequest();
  xhr.open('POST', '../save_score.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          console.log(xhr.responseText);
      }
  };
  xhr.send('score=' + encodeURIComponent(score) + '&game=' + encodeURIComponent(name));
}

// Function to restart the game
function restartGame() {
  gameOverFlag = false;
  arena.forEach(row => row.fill(0));
  player.score = 0;
  holdPiece = null; // Clear the held piece
  hasHeldPiece = false; // Reset the hold flag
  playerReset();
  updateScore();
  document.getElementById('game-over-screen').style.display = 'none';
  update();
}

// Function to go back to the previous page
function goBack() {
  window.location.href = 'speles.php'; // Adjust the URL as necessary
}
