<?php
$conn = mysqli_connect("localhost", "root", "", "fitzone");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
