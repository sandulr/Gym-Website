<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


include 'includes/header.php';
include 'includes/db.php';

$user_id = $_SESSION['user_id'];


$sqlUser = "SELECT fullname, email FROM users WHERE id = ?";
$stmtUser = mysqli_prepare($conn, $sqlUser);
mysqli_stmt_bind_param($stmtUser, "i", $user_id);
mysqli_stmt_execute($stmtUser);
$resultUser = mysqli_stmt_get_result($stmtUser);

// fetching membership plan name
$sqlMem = "SELECT m.name FROM memberships m 
           INNER JOIN user_memberships um ON m.id = um.membership_id
           WHERE um.user_id = ? AND um.status = 'active' LIMIT 1";
$stmtMem = mysqli_prepare($conn, $sqlMem);
mysqli_stmt_bind_param($stmtMem, "i", $user_id);
mysqli_stmt_execute($stmtMem);
$resultMem = mysqli_stmt_get_result($stmtMem);
$membership = mysqli_fetch_assoc($resultMem);

$plan_name = $membership ? $membership['plan_name'] : 'No active membership';

$row = mysqli_fetch_assoc($resultUser);

if ($row) {
    $name = $row['fullname'];
    $email = $row['email'];
} else {
    $name = "User";
    $email = "Not available";
}



?>

<div class="dashboard">
    <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    <p><strong>Membership Plan:</strong> <?php echo htmlspecialchars($plan_name); ?></p>

    <hr>

    <h3>Quick Actions</h3>
    <ul>
        <li><a href="classes.php">View Available Classes</a></li>
        <li><a href="trainers.php">Meet Our Trainers</a></li>
        
        <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
            <li><a href="membership.php">Upgrade Membership</a></li>
            <li><a href="contact.php">Ask for help</a></li>
        <?php endif; ?>
    </ul>
</div>

<?php include 'includes/footer.php'; ?>
