<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

require '../includes/db.php';


$data = json_decode(file_get_contents('php://input'), true);
$class_id = intval($data['id'] ?? 0);

if ($class_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid class ID']);
    exit();
}


$stmt = mysqli_prepare($conn, "DELETE FROM classes WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $class_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);


?>