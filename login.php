<?php

include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$errors = [];

function esc($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, fullname, password, role FROM users WHERE email = ? LIMIT 1");
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($conn)); 
        }
        
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name, $hash, $role);
        
        if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $hash)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['role'] = $role;
                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = 'Invalid username or password';
            }
        } else {
            $errors[] = 'Invalid username or password';
        }
        mysqli_stmt_close($stmt);
    }
}

include 'includes/header.php';
?>

<div class="form-container">
    <h2>Login</h2>
    <?php foreach ($errors as $e): ?>
        <p class="error"><?= esc($e) ?></p>
    <?php endforeach; ?>
    <form method="post">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
