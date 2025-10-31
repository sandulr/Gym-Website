<?php

include '../includes/db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $specialization = $_POST['specialties'];

    $photo_path = '';

    // photo upload
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] === 0){
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = "trainer_$id._".uniqid().".".$ext;
        $target = "../assets/images/".$photo_name;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);
        $photo_path = $photo_name;
    }

    $sql = "UPDATE trainers SET name=?, bio=?, specialties=?";
    $params = [$name, $bio, $specialization];

    if($photo_path){
        $sql .= ", photo=?";
        $params[] = $photo_path;
    }

    $sql .= " WHERE id=?";
    $params[] = $id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat("s", count($params)-1)."i", ...$params);

    if($stmt->execute()){
        echo "✅ Trainer updated successfully!";
    } else {
        echo "❌ Error: ".$conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
