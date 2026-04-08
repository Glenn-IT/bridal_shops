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

$result = $mysqli->query("
    SELECT id, event_name, status, 
           CONCAT(firstname, ' ', COALESCE(NULLIF(middlename,''),' '), ' ', lastname) AS client_name
    FROM bookings
    ORDER BY id DESC
");

// Fetch all booking details for modal
$allBookings = [];
$result2 = $mysqli->query("SELECT * FROM bookings ORDER BY id DESC");
while ($b = $result2->fetch_assoc()) {
    $allBookings[] = $b;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Events</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: #f8f9fa;
    }
    .sidebar {
      width: 250px;
      height: 100vh;
      background: linear-gradient(to bottom, #2c3e50, #34495e);
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      padding: 20px 0;
    }
    .sidebar .logo { text-align: center; margin-bottom: 15px; }
    .sidebar .logo img {
      width: 90px; height: 90px; border-radius: 50%; object-fit: cover;
    }
    .sidebar h4 { text-align: center; margin: 10px 0 20px; font-weight: bold; }
    .sidebar a {
      display: block; padding: 12px 25px; color: white; text-decoration: none;
      font-size: 15px; transition: all 0.3s;
    }
    .sidebar a:hover, .sidebar a.active {
      background: rgba(255, 255, 255, 0.2); border-radius: 8px;
    }
    .sidebar a i { margin-right: 10px; }
    .content { margin-left: 250px; padding: 30px; }
    
    /* Hide delete button */
    .btn-danger {
      display: none !important;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <img src="images/logo.webp" alt="Logo">
    </div>
    <h4>Admin Panel</h4>
    <a href="dashboard_admin.php"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
    <a href="manage_user.php"><i class="fa fa-users"></i> Manage Users</a>
    <a href="manage_events.php" class="active"><i class="fa fa-calendar"></i> Manage Events</a>
    <a href="reservations.php"><i class="fa fa-book"></i> New Reservations</a>
    <a href="confirmations.php"><i class="fa fa-check-circle"></i> Confirmations</a>
    <a href="reports.php"><i class="fa fa-chart-line"></i> Reports</a>
    <a href="messages.php"><i class="fa fa-comments"></i> Messages</a>
    <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <h2 class="mb-4">Event Booking Management</h2>

    <table class="table table-bordered table-striped bg-white shadow-sm">
      <thead class="table-dark">
        <tr>
          <th>Event Name</th>
          <th>Client Name</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['event_name']) ?></td>
            <td><?= htmlspecialchars($row['client_name']) ?></td>
            <td>
              <span class="badge bg-<?= 
                $row['status'] === 'Approved' ? 'success' : 
                ($row['status'] === 'Rejected' ? 'danger' : 'warning') ?>">
                <?= htmlspecialchars($row['status']) ?>
              </span>
            </td>
            <td>
              <button class="btn btn-sm btn-info text-white" onclick="viewBooking(<?= $row['id'] ?>)"><i class="fas fa-eye"></i> View</button>
              <a href="edit_booking.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
              <form method="POST" action="delete_booking.php" class="d-inline" onsubmit="return confirm('Delete this booking?');">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#2c2f48; color:#fff;">
        <h5 class="modal-title"><i class="fas fa-calendar-check me-2"></i> Booking Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body px-4 py-3">
        <div class="detail-row"><span class="detail-label">Full Name</span><span class="detail-value" id="m-name"></span></div>
        <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value" id="m-email"></span></div>
        <div class="detail-row"><span class="detail-label">Phone Number</span><span class="detail-value" id="m-phone"></span></div>
        <div class="detail-row"><span class="detail-label">Service Type</span><span class="detail-value" id="m-service"></span></div>
        <div class="detail-row"><span class="detail-label">Event Name</span><span class="detail-value" id="m-event"></span></div>
        <div class="detail-row"><span class="detail-label">Event Date & Time</span><span class="detail-value" id="m-datetime"></span></div>
        <div class="detail-row"><span class="detail-label">Location</span><span class="detail-value" id="m-location"></span></div>
        <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value" id="m-status"></span></div>
        <div class="detail-row"><span class="detail-label">Booked On</span><span class="detail-value" id="m-created"></span></div>
      </div>
      <div class="modal-footer">
        <a id="m-edit-btn" href="#" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<style>
  .detail-row { display:flex; gap:10px; padding:8px 0; border-bottom:1px solid #f0f0f0; }
  .detail-row:last-child { border-bottom:none; }
  .detail-label { font-weight:600; color:#2c2f48; min-width:145px; }
  .detail-value { color:#444; }
</style>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const bookings = <?= json_encode($allBookings) ?>;

    function viewBooking(id) {
      const b = bookings.find(x => x.id == id);
      if (!b) return;

      const fullname = [b.firstname, b.middlename, b.lastname].filter(Boolean).join(' ');
      const statusColors = { Approved:'success', Declined:'danger', Rejected:'danger', Pending:'warning' };
      const color = statusColors[b.status] || 'secondary';

      document.getElementById('m-name').textContent     = fullname;
      document.getElementById('m-email').textContent    = b.email || '—';
      document.getElementById('m-phone').textContent    = b.phone_number || '—';
      document.getElementById('m-service').textContent  = b.service_type;
      document.getElementById('m-event').textContent    = b.event_name;
      document.getElementById('m-datetime').textContent = formatDate(b.event_datetime);
      document.getElementById('m-location').textContent = b.location || '—';
      document.getElementById('m-created').textContent  = formatDate(b.created_at);
      document.getElementById('m-status').innerHTML     = `<span class="badge bg-${color}">${b.status}</span>`;
      document.getElementById('m-edit-btn').href        = `edit_booking.php?id=${b.id}`;

      new bootstrap.Modal(document.getElementById('bookingModal')).show();
    }

    function formatDate(dateStr) {
      if (!dateStr) return '—';
      const d = new Date(dateStr.replace(' ', 'T'));
      return d.toLocaleString('en-US', {
        year:'numeric', month:'long', day:'numeric',
        hour:'numeric', minute:'2-digit', hour12:true
      });
    }
  </script>
</body>
</html>
