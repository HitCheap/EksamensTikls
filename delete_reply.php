<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

include 'datubaze.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : null;
    $user_id = $_SESSION['id'];

    if ($comment_id !== null) {
        // Verify that the comment belongs to the user
        $sql = "SELECT lietotaja_id FROM komentari WHERE comment_id = ? AND lietotaja_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $comment_id, $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Delete the comment
            $stmt->close();
            $sql = "DELETE FROM komentari WHERE comment_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $comment_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Comment deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete comment']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Unauthorized action']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid comment ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
