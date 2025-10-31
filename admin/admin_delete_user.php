<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

require '../includes/db.php';

// reading JSON input
$data = json_decode(file_get_contents('php://input'), true);
$user_id = intval($data['id'] ?? 0);

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid user ID']);
    exit();
}

// prevent admin from deleting themselves!
if ($user_id == $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'error' => 'You cannot delete your own account']);
    exit();
}

// Delete user securely using prepared statement
$stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
