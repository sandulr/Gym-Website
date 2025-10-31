<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone Fitness Center</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/png" href="/assets/images/ico.png">
</head>
<body>

<header class="nav-menu main-header">
    <div class="logo">
        <a href="/index.php">FitZone</a>
    </div>
    <nav class="">
        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
        &#9776;
        </button>
        <div class="nav-links" id="nav-links">
        <a href="/index.php">Home</a>
        <a href="/blog.php">Blog</a>

        <?php if (!empty($_SESSION['user_id'])): ?>

            <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="/admin/manage_users.php">Manage Users</a>
                <a href="/admin/manage_classes.php">Manage Classes</a>
                <a href="/admin/manage_trainers.php">Manage Trainers</a>
                <a href="/admin/view_queries.php">View Queries</a>
            <?php else: ?>
                <a href="/classes.php">Classes</a>
                <a href="/trainers.php">Trainers</a>
                <a href="/membership.php">Membership</a>
                <a href="/contact.php">Contact</a>
                <a href="/dashboard.php">Dashboard</a>
            <?php endif; ?>

            <a href="/logout.php" class="logout-btn">Logout</a>
        <?php else: ?>
            <a href="/classes.php">Classes</a>
            <a href="/trainers.php">Trainers</a>
            <a href="/membership.php">Membership</a>
            <a href="/contact.php">Contact</a>
            <a href="/login.php" class="btn-login">Login</a>
            <a href="/register.php" class="btn-register">Join Now</a>
        <?php endif; ?>
        </div>
    </nav>
</header>
<main>
