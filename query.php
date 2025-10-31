<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');

    if ($message === '') {
        $errors[] = 'Please enter your query.';
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO queries (user_id, message) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "is", $user_id, $message);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Your query has been submitted successfully!';
        } else {
            $errors[] = 'Database error: ' . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="form-container">
    <h2>Submit a Query</h2>

    <?php foreach ($errors as $e): ?>
        <p class="error"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="message">Your Query:</label>
        <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        <button type="submit">Send Query</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
