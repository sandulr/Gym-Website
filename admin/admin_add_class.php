<?php

include '../includes/db.php';
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = ucfirst(trim($_POST['title']));
    $description = trim($_POST['description']);
    $schedule = trim($_POST['schedule']);
    $trainer_id = intval($_POST['trainer_id']);
    $capacity = intval($_POST['capacity']);

    $sql = "INSERT INTO classes (title, description, schedule, trainer_id, capacity)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $title, $description, $schedule, $trainer_id, $capacity);

    if ($stmt->execute()) {
        echo "✅ Class added successfully!";
    } else {
        echo "❌ Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
