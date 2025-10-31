<?php

include '../includes/db.php';
session_start();

// only allow admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';

?>

<style type="text/css">

.page-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 25px 15px;
    color: white;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    background: linear-gradient(-45deg, #ff6b6b, #fbc531, #1dd1a1, #5f27cd);
    background-size: 400% 400%;
    animation: gradientBG 10s ease infinite;
}

@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.page-header h1 {
    margin: 0;
    font-size: 30px;
    font-weight: 700;
    letter-spacing: 1px;
}

.page-header p {
    margin: 10px 0 0 0;
    font-size: 16px;
    font-weight: 400;
    color: rgba(255,255,255,0.85);
}

@media (max-width: 480px) {
    .page-header h1 {
        font-size: 22px;
    }
    .page-header p {
        font-size: 14px;
    }
}

</style>


<?php

// Default values 
$page_title = $page_title ?? "Dashboard";
$page_desc = $page_desc ?? "";
?>

<div style="margin-top: 7px;" class="page-header">
    <h1><?= htmlspecialchars($page_title) ?></h1>
    <?php if ($page_desc): ?>
        <p><?= htmlspecialchars($page_desc) ?></p>
    <?php endif; ?>


    <?php if (!empty($headerButton)): ?>
        <div class="header-actions" style="margin-top: 45px !important;">
            <?php if (!empty($headerButton)) echo $headerButton; ?>
        </div>
    <?php else: ?>
        <div class="header-actions">
        </div>
    <?php endif; ?>
</div>
