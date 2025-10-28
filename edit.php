<?php
session_start();

// Check if client is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Booking ID is missing or invalid.');
}

$id = intval($_GET['id']);
$username = $_SESSION['username'];

$mysqli = new mysqli("localhost", "root", "", "bridal_event_system");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$stmt = $mysqli->prepare("SELECT * FROM bookings WHERE id = ? AND username = ?");
$stmt->bind_param("is", $id, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Booking not found or you are not authorized to edit it.');
}

$booking = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username       = trim($_POST['username']);
    $fullname       = trim($_POST['fullname']);
    $service_type   = trim($_POST['service_type']);
    $event_name     = trim($_POST['event_name']);
    $event_datetime = trim($_POST['event_datetime']);
    $location       = trim($_POST['location']);

    if (!$fullname || !$service_type || !$event_name || !$event_datetime || !$location) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        $update = $mysqli->prepare("UPDATE bookings SET fullname = ?, service_type = ?, event_name = ?, event_datetime = ?, location = ? WHERE id = ? AND username = ?");
        $update->bind_param("sssssis", $fullname, $service_type, $event_name, $event_datetime, $location, $id, $username);

        if ($update->execute()) {
            echo "<script>
                    alert('Booking updated successfully!');
                    window.location.href = 'notifications.php';
                  </script>";
            exit();
        } else {
            echo "Error updating booking: " . $update->error;
        }

        $update->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3>Edit Your Booking</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Full Name</label>
      <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($booking['fullname']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Service Type</label>
      <input type="text" name="service_type" class="form-control" value="<?= htmlspecialchars($booking['service_type']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Event Name</label>
      <input type="text" name="event_name" class="form-control" value="<?= htmlspecialchars($booking['event_name']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Event Date & Time</label>
      <input type="datetime-local" name="event_datetime" class="form-control" 
        value="<?= date('Y-m-d\TH:i', strtotime($booking['event_datetime'])) ?>" required>
    </div>
    <div class="mb-3">
      <label>Location</label>
      <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($booking['location']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Booking</button>
    <a href="notifications.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
