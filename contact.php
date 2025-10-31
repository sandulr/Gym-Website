<?php
include 'includes/header.php';
include 'includes/db.php';


$nameLogged = '';
$emailLogged = '';

if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT fullname, email FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nameDb, $emailDb);
    if (mysqli_stmt_fetch($stmt)) {
        $nameLogged = $nameDb;
        $emailLogged = $emailDb;
    }
    mysqli_stmt_close($stmt);
}


$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $errors[] = 'All fields are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Thank you for contacting us! We will get back to you shortly.';
        } else {
            $errors[] = 'Database error: ' . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="form-container">
    <h2>Contact Us</h2>

    <?php foreach ($errors as $e): ?>
        <p class="error"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post" novalidate>
        <label for="name">Name:</label>
        

        <input id="name" name="name" type="text" required value="<?= htmlspecialchars($nameLogged ?: ($_POST['name'] ?? '')) ?>" <?= !empty($nameLogged) ? 'readonly' : '' ?> >

        <label for="email">Email:</label>

        <input id="email" name="email" type="email" required value="<?= htmlspecialchars($emailLogged ?: ($_POST['email'] ?? '')) ?>" <?= !empty($emailLogged) ? 'readonly' : '' ?> >

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

        <button type="submit">Send Message</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
