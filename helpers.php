<?php

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function is_logged_in() {
    return isset($_SESSION['user']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function is_admin() {
    return is_logged_in() && $_SESSION['user']['role'] === 'admin';
}

function is_staff() {
    return is_logged_in() && $_SESSION['user']['role'] === 'staff';
}
?>
