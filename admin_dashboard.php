<?php

require 'config.php';
require 'helpers.php';

// Ensure $mysqli is available from config.php
if (!isset($mysqli) || $mysqli->connect_error) {
    die("MySQLi connection not established. Check config.php.");
}

if (!is_admin()) {
    header('Location: login.php');
    exit;
}

// List queries
$result_queries = $mysqli->query("SELECT q.*, u.fullname as user_name FROM queries q LEFT JOIN users u ON q.user_id = u.id ORDER BY q.created_at DESC LIMIT 50");
if ($result_queries === false) {
    die("Query failed: " . htmlspecialchars($mysqli->error));
}
$queries = $result_queries->fetch_all(MYSQLI_ASSOC);
$result_queries->free(); // Free the result set

// List appointments
$result_appointments = $mysqli->query("SELECT a.*, u.fullname as user_name, c.title as class_title, t.name as trainer_name FROM appointments a LEFT JOIN users u ON a.user_id=u.id LEFT JOIN classes c ON a.class_id=c.id LEFT JOIN trainers t ON a.trainer_id=t.id ORDER BY a.created_at DESC LIMIT 50");
if ($result_appointments === false) {
    die("Query failed: " . htmlspecialchars($mysqli->error));
}
$appointments = $result_appointments->fetch_all(MYSQLI_ASSOC);
$result_appointments->free(); // Free the result set
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin - FitZone</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-4">
<div class="container">
  <h2>Admin Dashboard</h2>
  <p><a href="index.php">Back to site</a> | <a href="logout.php">Logout</a></p>

  <h4>Recent Queries</h4>
  <table class="table table-sm">
    <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Status</th><th>Reply</th></tr></thead>
    <tbody>
      <?php foreach($queries as $q): ?>
        <tr>
          <td><?=e($q['id'])?></td>
          <td><?=e($q['name']?:$q['user_name'])?></td>
          <td><?=e($q['email'])?></td>
          <td><?=e($q['subject'])?></td>
          <td><?=e(substr($q['message'],0,80))?></td>
          <td><?=e($q['status'])?></td>
          <td><?=e(substr($q['reply'],0,80))?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h4>Recent Appointments</h4>
  <table class="table table-sm">
    <thead><tr><th>#</th><th>User</th><th>Class</th><th>Trainer</th><th>Date</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach($appointments as $a): ?>
        <tr>
          <td><?=e($a['id'])?></td>
          <td><?=e($a['user_name'])?></td>
          <td><?=e($a['class_title'])?></td>
          <td><?=e($a['trainer_name'])?></td>
          <td><?=e($a['appointment_date'])?></td>
          <td><?=e($a['status'])?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div></body></html>
