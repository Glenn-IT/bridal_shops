<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$eventName = isset($_GET['events']) ? htmlspecialchars($_GET['events']) : '';

// Connect to DB
$mysqli = new mysqli('localhost', 'root', '', 'bridal_event_system');
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if user wants to edit
$editMode = false;
$bookingData = [
    'id' => '',
    'fullname' => '',
    'service_type' => '',
    'event_name' => $eventName,
    'event_datetime' => '',
    'location' => ''
];

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $bookingId = intval($_GET['edit']);
    $stmt = $mysqli->prepare("SELECT * FROM bookings WHERE id = ? AND username = ?");
    $stmt->bind_param("is", $bookingId, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        $bookingData = $row;
        $editMode = true;
    }
    $stmt->close();
}

// Fetch latest booking for the edit button
$stmt = $mysqli->prepare("SELECT id FROM bookings WHERE username = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$hasBookingToEdit = false;
$editBookingId = null;
if ($row = $result->fetch_assoc()) {
    $hasBookingToEdit = true;
    $editBookingId = $row['id'];
}
$stmt->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Now</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      padding-top: 60px;
    }

    .container {
      max-width: 650px;
      background-color: #ffffff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #6c5ce7;
      margin-bottom: 25px;
    }

    label {
      font-weight: 600;
      margin-top: 12px;
    }

    input, select, textarea {
      margin-top: 5px;
      border-radius: 10px !important;
    }

    .btn-action {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      border-radius: 10px;
    }

    .btn-group-action {
      display: flex;
      gap: 10px;
      margin-top: 25px;
      justify-content: center;
    }
  </style>
</head>
<body>

<div class="container">
  <h2><?= $editMode ? "Edit Reservation" : "Book a Reservation" ?></h2>
  
  <form id="bookingForm" action="submit_booking.php" method="POST">
    <?php if ($editMode): ?>
      <input type="hidden" name="booking_id" value="<?= $bookingData['id'] ?>">
    <?php endif; ?>

    <label for="fullname">Full Name</label>
    <input type="text" name="fullname" id="fullname" class="form-control" required value="<?= htmlspecialchars($bookingData['fullname']) ?>">

    <label for="service_type">Service Type</label>
    <select name="service_type" id="service_type" class="form-control" required>
      <option value="">-- Select Gown Type --</option>
      <?php
      $types = ["Wedding Gown", "Ball Gown", "Evening Gown", "Debutante Gown", "Prom Gown", "Cocktail Dress", "Custom Tailored Gown"];
      foreach ($types as $type) {
          $selected = ($bookingData['service_type'] === $type) ? "selected" : "";
          echo "<option value='$type' $selected>$type</option>";
      }
      ?>
    </select>

    <label for="event_name">Event Name</label>
    <input type="text" name="event_name" id="event_name" class="form-control" value="<?= htmlspecialchars($bookingData['event_name']) ?>" required>

    <label for="event_datetime">Event Date & Time</label>
    <input type="datetime-local" name="event_datetime" id="event_datetime" class="form-control" required value="<?= !empty($bookingData['event_datetime']) ? date('Y-m-d\TH:i', strtotime($bookingData['event_datetime'])) : '' ?>">

    <label for="location">Event Location</label>
    <textarea name="location" id="location" class="form-control" rows="3" required><?= htmlspecialchars($bookingData['location']) ?></textarea>

    <div class="btn-group-action">
      <button type="button" class="btn btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#confirmModal">
        <?= $editMode ? "Update Reservation" : "Submit Reservation" ?>
      </button>

      <?php if (!$editMode && $hasBookingToEdit): ?>
        <a href="?edit=<?= $editBookingId ?>" class="btn btn-warning btn-action">Edit My Booking</a>
      <?php endif; ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?= $editMode ? "Confirm Update" : "Confirm Reservation" ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to <?= $editMode ? "update" : "submit" ?> this booking?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="<?= $editMode ? "update_booking" : "submit_booking" ?>" class="btn btn-success">
              <?= $editMode ? "Update" : "Confirm" ?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
