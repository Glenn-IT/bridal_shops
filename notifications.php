<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit();
}

// Fetch user details for display
$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT firstname, middlename, lastname FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$fullname = $user ? trim($user['firstname'] . ' ' . $user['middlename'] . ' ' . $user['lastname']) : $username;

$mysqli = new mysqli("localhost", "root", "", "bridal_event_system");
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// ✅ Delete individual notification (only for this user)
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $mysqli->query("DELETE FROM notifications WHERE id=$delete_id AND username='$username'");
    header("Location: notifications.php");
    exit();
}

// ✅ Mark all as read for this user
$mysqli->query("UPDATE notifications SET is_read=1 WHERE username='$username'");

// ✅ Fetch all notifications for this user
$result = $mysqli->query("SELECT * FROM notifications WHERE username='$username' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Notifications</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f4f4;
      font-family: Arial, sans-serif;
      padding-top: 70px;
    }
    /* Navbar Styles */
    .navbar-custom {
      background: linear-gradient(135deg, #d6336c, #e76f51);
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
      color: white !important;
    }
    .nav-link {
      color: white !important;
      margin: 0 10px;
    }
    .nav-link:hover {
      color: #ffd700 !important;
    }
    .btn-contact {
      background-color: #fff;
      color: #d6336c;
      border: none;
    }
    .btn-contact:hover {
      background-color: #ffd700;
      color: #d6336c;
    }
    .container {
      max-width: 800px;
      margin-top: 50px;
    }
    .info-message {
      background: #fff3cd;
      border: 1px solid #ffeeba;
      padding: 15px;
      border-radius: 5px;
      color: #856404;
      margin-bottom: 25px;
    }
    .list-group-item {
      background-color: #fff;
      border: 1px solid #ddd;
    }
    .notif-actions {
      display: flex;
      gap: 10px;
    }
    .notif-message {
      flex: 1;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard_client.php">Mae's Bridal Shop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background-color: white;">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="dashboard_client.php#hero">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="notifications.php">Notifications</a></li>
        <li class="nav-item"><a class="nav-link" href="booking_history.php">Booking History</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="text-white"><?= htmlspecialchars($fullname) ?></span>
        <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        <a href="contact.php" class="btn btn-sm btn-contact">Contact Us</a>
      </div>
    </div>
  </div>
</nav>

<div class="container bg-white p-4 rounded shadow-sm">
  <h3 class="mb-4 text-primary">Your Notifications</h3>

  <!-- Instructional Message -->
  <div class="info-message">
    <strong>Note:</strong> Your booking will be reviewed by the admin. We will send a confirmation to your cellphone number. <br>
    Please wait for your confirmation.
  </div>

  <?php if ($result->num_rows > 0): ?>
    <ul class="list-group">
      <?php while ($row = $result->fetch_assoc()): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div class="notif-message">
            <?= htmlspecialchars($row['message']) ?><br>
            <small class="text-muted"><?= date("M d, Y h:i A", strtotime($row['created_at'])) ?></small>
          </div>

          <?php if (strpos($row['message'], 'approved') !== false): ?>
            <div class="notif-actions">
              <a href="view_booking_client.php?notif_id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
              <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this notification?')">Delete</a>
            </div>
          <?php endif; ?>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <div class="alert alert-info">No notifications yet.</div>
  <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
