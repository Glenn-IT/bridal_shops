<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "bridal_event_system");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$booking = null;
$success = false;
$error = '';

if (isset($_GET['id'])) {
    $bookingId = intval($_GET['id']);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullname = $_POST['fullname'];
        $event_name = $_POST['event_name'];
        $event_datetime = $_POST['event_datetime'];
        $location = $_POST['location'];
        $service_type = $_POST['service_type'];
        $status = $_POST['status'];

        $stmt = $mysqli->prepare("UPDATE bookings SET fullname=?, event_name=?, event_datetime=?, location=?, service_type=?, status=? WHERE id=?");
        $stmt->bind_param("ssssssi", $fullname, $event_name, $event_datetime, $location, $service_type, $status, $bookingId);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Failed to update booking.";
        }

        $stmt->close();
    }

    // Fetch booking details
    $stmt = $mysqli->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();
} else {
    header('Location: view_bookings.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h3 class="mb-4">Edit Booking</h3>

  <?php if ($success): ?>
    <div class="alert alert-success">Booking updated successfully!</div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($booking): ?>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($booking['fullname']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Event Name</label>
      <input type="text" name="event_name" class="form-control" value="<?= htmlspecialchars($booking['event_name']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Event Date & Time</label>
      <input type="datetime-local" name="event_datetime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($booking['event_datetime'])) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Location</label>
      <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($booking['location']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Service Type</label>
      <input type="text" name="service_type" class="form-control" value="<?= htmlspecialchars($booking['service_type']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select" required>
        <option value="Pending" <?= $booking['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Approved" <?= $booking['status'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
        <option value="Rejected" <?= $booking['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Booking</button>
    <a href="view_bookings.php" class="btn btn-secondary">Cancel</a>
  </form>
  <?php else: ?>
    <div class="alert alert-warning">Booking not found.</div>
  <?php endif; ?>
</div>

</body>
</html>
