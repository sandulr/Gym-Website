<?php
include 'includes/header.php';
include 'includes/db.php';

$sql = "SELECT * FROM memberships ORDER BY price ASC";
$result = mysqli_query($conn, $sql);
?>

<div class="page-container">
    <h1 style="margin-bottom: 5px;">Membership Plans</h1>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="card-grid">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card membership-card">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><strong>Price:</strong> $<?= number_format($row['price'], 2) ?></p>
                    <p><?= nl2br(htmlspecialchars($row['benefits'])) ?></p>
                    <p><strong>Duration:</strong> <?= $row['duration_months'] ?> <?php if ($row['duration_months'] == 1): ?> month <?php else: ?> months <?php endif; ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No membership plans available.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
