<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') exit;

require '../includes/db.php';
$data = json_decode(file_get_contents("php://input"), true);

$id = intval($data['id'] ?? 0);
$name = mysqli_real_escape_string($conn, $data['name'] ?? '');
$email = mysqli_real_escape_string($conn, $data['email'] ?? '');
$role = strtolower(mysqli_real_escape_string($conn, $data['role'] ?? ''));

if ($id && $name && $email && $role) {
    $sql = "UPDATE users SET fullname='$name', email='$email', role='$role' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false,'error'=>mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success'=>false,'error'=>'Invalid data']);
}
