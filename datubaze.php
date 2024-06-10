<?php
  $host = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'majaslapa';
  
  $conn = new mysqli($host, $username, $password, $database);
  
  if ($conn->connect_error) {
      die('Datubāzes savienojums neveiksmīgs: ' . $conn->connect_error);
  }
?>