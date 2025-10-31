<?php
include 'includes/header.php';
include 'includes/db.php';

$sql = "SELECT * FROM classes ORDER BY title ASC";
$result = mysqli_query($conn, $sql);
?>

<div class="page-container">
    <h1>Fitness Classes</h1>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="card-grid">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <img src="assets/images/class.jpg" alt="<?= htmlspecialchars($row['title']) ?>">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    <p><strong>Schedule:</strong> <?= htmlspecialchars($row['schedule']) ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No classes available at the moment.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
