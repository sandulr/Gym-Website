<?php

require 'config.php';
require 'helpers.php';

// Check if a user is logged in
if (is_logged_in()) {
    $role = $_SESSION['user']['role'];

    // Redirect based on user role using a switch statement
    switch ($role) {
        case 'admin':
            header('Location: admin_dashboard.php');
            break;
        case 'staff':
            header('Location: staff_dashboard.php');
            break;
        default:
            header('Location: index.php');
            break;
    }
    exit;
} else {
    // Redirect to login page if user is not logged in
    header('Location: login.php');
    exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Book Appointment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-4">
<div class="container col-md-6">
  <h2>Book Appointment</h2>
  <?php if($errors): ?><div class="alert alert-danger"><?php foreach($errors as $e) echo '<div>'.e($e).'</div>'; ?></div><?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label>Select class (optional)</label>
      <select name="class_id" class="form-select">
        <option value="">-- none --</option>
        <?php foreach($classes as $c): ?>
          <option value="<?=e($c['id'])?>"><?=e($c['title'])?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Select trainer (optional)</label>
      <select name="trainer_id" class="form-select">
        <option value="">-- none --</option>
        <?php foreach($trainers as $t): ?>
          <option value="<?=e($t['id'])?>"><?=e($t['name'])?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Appointment date & time</label>
      <input type="datetime-local" name="appointment_date" class="form-control">
    </div>
    <button class="btn btn-primary">Book</button>
  </form>
</div></body></html>
