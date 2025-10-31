<?php

include '../includes/db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = htmlspecialchars($_POST['name']);
    $bio = htmlspecialchars($_POST['bio']);
    $specialization = htmlspecialchars($_POST['specialties']);

    $photo_path = '';

    if(isset($_FILES['photo']) && $_FILES['photo']['error'] === 0){
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = "trainer_".uniqid().".".$ext;
        $target = "../assets/images/".$photo_name;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);
        $photo_path = $photo_name;
    }

    $sql = "INSERT INTO trainers (name, bio, specialties, photo) VALUES (?, ?, ?, ?)";

    if($photo_path){
        $imgUSend = $photo_path;
    }else{
        $imgUSend = "def_trainer.png";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $bio, $specialization, $imgUSend);

    if($stmt->execute()){
        echo "✅ Trainer added successfully!";
    } else {
        echo "❌ Error: ".$conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
