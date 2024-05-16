var blockSize = 30;
var rows = 17; 
var columns = 38; 
var board;
var context;
 
var snakeX = blockSize * 5;
var snakeY = blockSize * 5;
 

var speedX = 0;  
var speedY = 0;  
 
var snakeBody = [];
 
var foodX;
var foodY;

var score = 0;

var gameOver = false;

var endscreen;
 
window.onload = function () {
    // Set board height and width
    board = document.getElementById("board");
    board.height = rows * blockSize;
    board.width = columns * blockSize;
    context = board.getContext("2d");

    placeFood();
    showScore();
    document.addEventListener("keyup", changeDirection);  //for movements
    // Set snake speed
    setInterval(update, 1000 / 8);
    end = document.getElementById("end");
    end.style.display = "none";
}
 
function update() {
    if (gameOver) {
        end.style.display = "block";
        document.getElementById("endscore").innerHTML = score;
        document.getElementById("score").style.display = "none";
        document.getElementById("scoretext").style.display = "none";
        return; 
    }

    showScore();
    // Background of the Game
    context.fillStyle = "#262626";
    context.fillRect(0, 0, board.width, board.height);
 
    // Set food color and position
    context.fillStyle = "red";
    context.fillRect(foodX, foodY, blockSize, blockSize);
 
    if (snakeX == foodX && snakeY == foodY) {
        snakeBody.push([foodX, foodY]);
        placeFood();
        score++;
    }
 
    // body of snake will grow
    for (let i = snakeBody.length - 1; i > 0; i--) {
        // it will store previous part of snake to the current part
        snakeBody[i] = snakeBody[i - 1];
    }
    if (snakeBody.length) {
        snakeBody[0] = [snakeX, snakeY];
    }
 
    context.fillStyle = "white";
    snakeX += speedX * blockSize; //updating Snake position in X coordinate.
    snakeY += speedY * blockSize;  //updating Snake position in Y coordinate.
    context.fillRect(snakeX, snakeY, blockSize, blockSize);
    for (let i = 0; i < snakeBody.length; i++) {
        context.fillRect(snakeBody[i][0], snakeBody[i][1], blockSize, blockSize);
    }
 
    if (snakeX < 0
        || snakeX >= columns * blockSize
        || snakeY < 0
        || snakeY >= rows * blockSize) {
         
        // Out of bound condition
        gameOver = true;
        
    }
 
    for (let i = 0; i < snakeBody.length; i++) {
        if (snakeX == snakeBody[i][0] && snakeY == snakeBody[i][1]) {
             
            // Snake eats own body
            gameOver = true;
        }
    }
}
 
// Movement of the Snake 
function changeDirection(e) {
    if (e.code == "ArrowUp" && speedY != 1) {
        // If up arrow key pressed with this condition...
        // snake will not move in the opposite direction
        speedX = 0;
        speedY = -1;
    }
    else if (e.code == "ArrowDown" && speedY != -1) {
        //If down arrow key pressed
        speedX = 0;
        speedY = 1;
    }
    else if (e.code == "ArrowLeft" && speedX != 1) {
        //If left arrow key pressed
        speedX = -1;
        speedY = 0;
    }
    else if (e.code == "ArrowRight" && speedX != -1) {
        //If Right arrow key pressed
        speedX = 1;
        speedY = 0;
    }
}
 
// Randomly place food
function placeFood() {
 
    // in x coordinates.
    foodX = Math.floor(Math.random() * columns) * blockSize;
     
    //in y coordinates.
    foodY = Math.floor(Math.random() * rows) * blockSize;
}

function showScore(){
    document.getElementById("score").innerHTML = score;
}



