<?php

session_start();
include '../includes/db.php';

// -- only admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "Unauthorized";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $title = $data['title'];
    $description = $data['description'];
    $schedule = $data['schedule'];
    $capacity = $data['capacity'];

    if ($id > 0 && !empty($title) && !empty($description) && !empty($schedule) && $capacity > 0) {
        $stmt = $conn->prepare("UPDATE classes SET title = ?, description = ?, schedule = ?, capacity = ? WHERE id = ?");
        $stmt->bind_param("sssii", $title, $description, $schedule, $capacity, $id);

        if ($stmt->execute()) {
            return json_encode(array("msg"=>"success"));
        } else {
            return json_encode(array("msg"=>"error"));
        }

        $stmt->close();
    } else {
        return json_encode(array("msg"=>"invalid"));
    }
}
$conn->close();
?>
