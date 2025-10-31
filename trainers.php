<?php
include 'includes/header.php';
include 'includes/db.php';

$sql = "SELECT * FROM trainers ORDER BY name ASC";
$result = mysqli_query($conn, $sql);
?>

<div class="page-container">
    <h1>Our Trainers</h1>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="card-grid">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <?php if ($row['photo']): ?>
                        <img src="assets/images/<?= htmlspecialchars($row['photo']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><strong>Specialty:</strong> <?= htmlspecialchars($row['specialties']) ?></p>
                    <p><?= nl2br(htmlspecialchars($row['bio'])) ?></p>
                    
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No trainers found.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
