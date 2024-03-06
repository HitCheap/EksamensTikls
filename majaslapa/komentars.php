<?php

  session_start();

    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'majaslapa';

 
    $conn = new mysqli($host, $username, $password, $database);

  
    if($conn -> connect_error) {
      echo 'Database connection failed.';
    }

    if($_SERVER['REQUEST_METHOD'] == "POST") {

      $teksts = $_POST['teksts'];
      $lietotaja_id = $_SESSION['id'];
  
        $sql = $conn -> prepare("INSERT INTO komentari (teksts, lietotaja_id) VALUES (?, ?)");
        $sql -> bind_param("ss", $teksts, $lietotaja_id);
  
  
        if($sql -> execute()) {
          header("Location: index.php");
        } 
    }

?>