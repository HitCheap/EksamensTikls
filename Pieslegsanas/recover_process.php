<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $choice = $_POST['recover_choice'];
    
    if ($choice == 'password') {
        header('Location: password_reset.php');
    } else if ($choice == 'username') {
        header('Location: username_recovery.php');
    }
    exit();
}
?>
