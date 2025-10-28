<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "bridal_event_system");
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Use session username if logged in, otherwise 'Guest'
$username = $_SESSION['username'] ?? 'Angelie';

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
</body>
</html>
