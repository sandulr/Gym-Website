<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

require '../includes/db.php';


$data = json_decode(file_get_contents('php://input'), true);
$query_id = intval($data['id'] ?? 0);

if ($query_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid quey ID']);
    exit();
}

// Delete user securely using prepared statement
$stmt = mysqli_prepare($conn, "DELETE FROM contacts WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $query_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
