<?php
require 'config.php';

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM bookings");
$totalEvents = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM bookings");
$totalReservations = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM bookings WHERE status = 'Pending'");
$pending = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM notifications WHERE is_read = 0");
$unread = $stmt->fetch()['total'];

echo <<<HTML
<div class="card purple">
  <i class="fas fa-calendar-check"></i>
  <h3>$totalEvents</h3>
  <p>Total Events</p>
</div>
<div class="card blue">
  <i class="fas fa-users"></i>
  <h3>$totalReservations</h3>
  <p>Total Reservations</p>
</div>
<div class="card yellow">
  <i class="fas fa-clock"></i>
  <h3>$pending</h3>
  <p>Pending Confirmations</p>
</div>
<div class="card red">
  <i class="fas fa-bell"></i>
  <h3>$unread</h3>
  <p>Unread Notifications</p>
</div>
HTML;
