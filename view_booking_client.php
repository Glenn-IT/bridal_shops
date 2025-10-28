<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "bridal_event_system");
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}


$booking = $mysqli->query("SELECT * FROM bookings WHERE status='Approved' ORDER BY event_datetime DESC LIMIT 1");
$data = $booking->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Your Booking Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container bg-white p-4 rounded shadow-sm">
  <h3 class="mb-4 text-primary">Booking Details</h3>
  <?php if ($data): ?>
    <ul class="list-group">
      <li class="list-group-item"><strong>Event Name:</strong> <?= $data['event_name'] ?></li>
      <li class="list-group-item"><strong>Service Type:</strong> <?= $data['service_type'] ?></li>
      <li class="list-group-item"><strong>Date/Time:</strong> <?= $data['event_datetime'] ?></li>
      <li class="list-group-item"><strong>Status:</strong> <?= $data['status'] ?></li>
    </ul>
    <a href="notifications.php" class="btn btn-secondary mt-3">Back</a>
  <?php else: ?>
    <div class="alert alert-warning">No approved booking found.</div>
  <?php endif; ?>
</div>
</body>
</html>
