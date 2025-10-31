<?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

<section class="hero">
    <div class="hero-text">
        <h1>Welcome to FitZone Fitness Center</h1>
        <p>Your journey to a healthier, stronger, and more confident you starts here.</p>

        <?php if (empty($_SESSION['user_id'])): ?>
          <a href="register.php" class="btn">Join Now</a>
        <?php endif; ?>
        
    </div>
</section>

<section class="features">
    <div class="feature">
        <h3>State-of-the-art Equipment</h3>
        <p>Train with top-tier machines and tools for maximum results.</p>
    </div>
    <div class="feature">
        <h3>Expert Trainers</h3>
        <p>Certified professionals to guide you every step of the way.</p>
    </div>
    <div class="feature">
        <h3>Personalized Plans</h3>
        <p>Workouts and diets tailored to your unique needs.</p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
