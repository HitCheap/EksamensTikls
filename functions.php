<?php
// functions.php

include 'datubaze.php';

// functions.php

function addNotification($conn, $userID, $type, $message) {
    $sql = $conn->prepare("INSERT INTO notifications (user_id, type, message) VALUES (?, ?, ?)");
    $sql->bind_param("iss", $userID, $type, $message);
    $sql->execute();
    $sql->close();
}


// Other common functions can be added here as needed
?>
