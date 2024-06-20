<?php
session_start();
header('Content-Type: application/json'); // Ensure the response is JSON

require 'datubaze.php'; // Update with your actual database connection file

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Assuming you have the user ID stored in the session
        $user_id = $_SESSION['user_id'];

        // Check if the new password is at least 8 characters long
        if (strlen($new_password) < 8) {
            echo json_encode(['error' => 'Jaunā parolei jābūt vismaz 8 simbolus garai.']);
            exit;
        }

        if ($new_password !== $confirm_password) {
            echo json_encode(['error' => 'Jaunā parole nesakrīt ar apstiprināto paroli.']);
            exit;
        }

        // Fetch the current password hash from the database
        $query = $conn->prepare('SELECT parole FROM lietotaji WHERE id = ?'); // Change 'user_password' to the actual column name
        if (!$query) {
            throw new Exception('Database query preparation failed.');
        }
        $query->bind_param('i', $user_id);
        $query->execute();
        $query->bind_result($stored_password_hash);
        if (!$query->fetch()) {
            echo json_encode(['error' => 'User not found.']);
            $query->close();
            exit;
        }
        $query->close();

        // Verify the current password
        if (!password_verify($current_password, $stored_password_hash)) {
            echo json_encode(['error' => 'Pašreizējā parole nav pareiza.']);
            exit;
        }

        // Check if the new password is the same as the current password
        if ($new_password === $current_password) {
            echo json_encode(['error' => 'Jaunā parole nevar būt kā iepriekšējā parole.']);
            exit;
        }

        // Hash the new password
        $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the password in the database
        $update_query = $conn->prepare('UPDATE lietotaji SET parole = ? WHERE id = ?'); // Change 'user_password' to the actual column name
        if (!$update_query) {
            throw new Exception('Database update query preparation failed.');
        }
        $update_query->bind_param('si', $new_password_hash, $user_id);
        if ($update_query->execute()) {
            echo json_encode(['success' => 'Parole veiksmīgi atjaunināta.']);
        } else {
            throw new Exception('Kļūda atjauninot paroli.');
        }
        $update_query->close();
        $conn->close();
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>