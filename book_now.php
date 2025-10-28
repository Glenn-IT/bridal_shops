<?php
session_start();
$eventName = isset($_GET['events']) ? htmlspecialchars($_GET['events']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Now</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container bg-white p-4 rounded shadow-sm mt-5" style="max-width: 600px;">
  <h2 class="text-center text-primary mb-4">Book a Reservation</h2>

  <!-- ✅ Show Messages -->
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= $_SESSION['error']; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $_SESSION['success']; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <form id="bookingForm" action="submit_booking.php" method="POST">
    <!-- Firstname -->
    <div class="mb-3">
      <label for="firstname" class="form-label">First Name</label>
      <input type="text" name="firstname" id="firstname" class="form-control" required>
    </div>

    <!-- Middlename -->
    <div class="mb-3">
      <label for="middlename" class="form-label">Middle Name</label>
      <input type="text" name="middlename" id="middlename" class="form-control">
    </div>

    <!-- Lastname -->
    <div class="mb-3">
      <label for="lastname" class="form-label">Last Name</label>
      <input type="text" name="lastname" id="lastname" class="form-control" required>
    </div>

    <!-- Email -->
    <div class="mb-3">
      <label for="email" class="form-label">Email Address</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="e.g. client@example.com" required>
    </div>

    <!-- Phone -->
    <div class="mb-3">
      <label for="phone_number" class="form-label">Phone Number</label>
      <input type="tel" name="phone_number" id="phone_number" class="form-control"
             required pattern="[0-9]{11}" placeholder="e.g. 09171234567">
    </div>

    <!-- Service Type -->
    <div class="mb-3">
      <label for="service_type" class="form-label">Service Type</label>
      <select name="service_type" id="service_type" class="form-control" required>
        <option value="">-- Select Gown Type --</option>
        <option value="Wedding Gown">Wedding Gown</option>
        <option value="Ball Gown">Ball Gown</option>
        <option value="Evening Gown">Evening Gown</option>
        <option value="Debutante Gown">Debutante Gown</option>
      </select>
    </div>

    <!-- Event Name -->
    <div class="mb-3">
      <label for="event_name" class="form-label">Event Name</label>
      <input type="text" name="event_name" id="event_name" class="form-control"
             value="<?= $eventName ?>" placeholder="e.g. Jane's Wedding" required>
    </div>

    <!-- Event DateTime -->
    <div class="mb-3">
      <label for="event_datetime" class="form-label">Event Date & Time</label>
      <input type="datetime-local" name="event_datetime" id="event_datetime" class="form-control" required>
    </div>

    <!-- Event Location -->
    <div class="mb-3">
      <label for="location" class="form-label">Event Location</label>
      <textarea name="location" id="location" class="form-control" rows="3" required></textarea>
    </div>

    <!-- Submit with Modal -->
    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#confirmModal">
      Submit Reservation
    </button>
  </form>
</div>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirm Reservation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to submit this reservation?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmBtn" class="btn btn-primary">Confirm</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('confirmBtn').addEventListener('click', function () {
    document.getElementById('bookingForm').submit();
  });
</script>
</body>
</html>
