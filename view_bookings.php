<?php
session_start();


$mysqli = new mysqli("localhost", "root", "", "bridal_event_system");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch all bookings
$bookings = $mysqli->query("SELECT id, fullname, event_name, event_datetime, service_type, status FROM bookings ORDER BY created_at DESC");

// Check if viewing a single booking
$singleBooking = null;
if (isset($_GET['id'])) {
    $bookingId = intval($_GET['id']);
    $stmt = $mysqli->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $singleBooking = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Bookings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f1f2f6; }
    .container { margin-top: 40px; }
    .card { margin-bottom: 20px; }
  </style>
</head>
<body>

<div class="container">
  <h2 class="text-center mb-4">Event Booking Management</h2>

  <?php if ($singleBooking): ?>
    <div class="card border-info">
      <div class="card-header bg-info text-white">
        Booking Details
        <a href="view_bookings.php" class="btn btn-sm btn-light float-end">Back</a>
      </div>
      <div class="card-body">
        <p><strong>Full Name:</strong> <?= htmlspecialchars($singleBooking['fullname']) ?></p>
        <p><strong>Service Type:</strong> <?= htmlspecialchars($singleBooking['service_type']) ?></p>
        <p><strong>Event Name:</strong> <?= htmlspecialchars($singleBooking['event_name']) ?></p>
        <p><strong>Event Date & Time:</strong> <?= date('F j, Y g:i A', strtotime($singleBooking['event_datetime'])) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($singleBooking['location']) ?></p>
        <p><strong>Status:</strong> 
          <span class="badge bg-<?= 
            $singleBooking['status'] === 'Approved' ? 'success' :
            ($singleBooking['status'] === 'Rejected' ? 'danger' : 'warning') ?>">
            <?= htmlspecialchars($singleBooking['status']) ?>
          </span>
        </p>
      </div>
    </div>
  <?php else: ?>
    <table class="table table-bordered table-striped bg-white">
      <thead class="table-dark">
        <tr>
          <th>Event Name</th>
          <th>Client Name</th>
          <th>Service Type</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $bookings->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['event_name']) ?></td>
            <td><?= htmlspecialchars($row['fullname']) ?></td>
            <td><?= htmlspecialchars($row['service_type']) ?></td>
            <td><?= date('M d, Y g:i A', strtotime($row['event_datetime'])) ?></td>
            <td>
              <span class="badge bg-<?= 
                $row['status'] === 'Approved' ? 'success' :
                ($row['status'] === 'Rejected' ? 'danger' : 'warning') ?>">
                <?= htmlspecialchars($row['status']) ?>
              </span>
            </td>
            <td>
              <a href="view_bookings.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
              <a href="admin_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
              <form method="POST" action="delete_booking.php" class="d-inline" onsubmit="return confirm('Delete this booking?');">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

</body>
</html>
