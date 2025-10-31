<?php
require 'includes/db.php';
session_start();


if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $role = $_POST['role'] ?? 'customer';

    if ($name === '' || $email === '' || $password === '' || $password2 === '') {
        $errors[] = 'All fields are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if ($password !== $password2) {
        $errors[] = 'Passwords do not match.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($role !== 'admin' && $role !== 'customer' && $role !== 'staff') {
        $errors[] = 'Invalid role selected.';
    }

    if (empty($errors)) {
        $email = mysqli_real_escape_string($conn, $email);
        $name = mysqli_real_escape_string($conn, $name);
        $role = mysqli_real_escape_string($conn, $role);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // if email already exists
        $check_sql = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) {
            $errors[] = 'Email already exists.';
        } else {
            $sql = "INSERT INTO users (fullname, email, password, role) VALUES ('$name', '$email', '$hashedPassword', '$role')";

            if (mysqli_query($conn, $sql)) {
                // the inserted user's ID
                $user_id = mysqli_insert_id($conn);

                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['role'] = $role;

                //after successful registration, edirecting  to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $errors[] = 'Error: ' . mysqli_error($conn);
            }


          
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<div class="form-container">
    <h2>Register</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <?php foreach ($errors as $e): ?>
                <p><?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-box"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="" id="registerForm">
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="email">Email Address:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="password2">Confirm Password:</label>
        <input type="password" name="password2" id="password2" required>

        <label for="role">Account Type:</label>
        <select name="role" id="role" required>
            <option value="customer">Customer</option>
            <option value="staff">Management Staff</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" class="btn-primary">Register</button>
    </form>

    <div id="jsErrorBox" class="error-box" style="display:none;"></div>
</div>

<script>
document.getElementById("registerForm").addEventListener("submit", function(event) {
    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;
    let password2 = document.getElementById("password2").value;
    let role = document.getElementById("role").value;

    let errorMessage = "";

    if (name === "" || email === "" || password === "" || password2 === "") {
        errorMessage = "All fields are required.";
    }
    else if (!/^[^ ]+@[^ ]+\.[a-z]{2,}$/i.test(email)) {
        errorMessage = "Invalid email format.";
    }
    else if (password.length < 6) {
        errorMessage = "Password must be at least 6 characters.";
    }
    else if (password !== password2) {
        errorMessage = "Passwords do not match.";
    }
    else if (!["customer", "staff", "admin"].includes(role)) {
        errorMessage = "Invalid role selected.";
    }

    if (errorMessage !== "") {
        event.preventDefault();
        let errorBox = document.getElementById("jsErrorBox");
        errorBox.innerHTML = `<p>⚠️ ${errorMessage}</p>`;
        errorBox.style.display = "block";
    }
});
</script>

<?php include 'includes/footer.php'; ?>
