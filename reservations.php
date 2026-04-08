<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "bridal_event_system");
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($_GET['action'] === 'read') {
        $mysqli->query("UPDATE bookings SET status='Read' WHERE id=$id");
    } elseif ($_GET['action'] === 'unread') {
        $mysqli->query("UPDATE bookings SET status='Pending' WHERE id=$id");
    } elseif ($_GET['action'] === 'delete') {
        $mysqli->query("DELETE FROM bookings WHERE id=$id");
    }

    header("Location: reservations.php");
    exit();
}

// ✅ Fetch bookings with firstname, middlename, lastname
$query = "
    SELECT id, firstname, middlename, lastname, event_name, service_type, event_datetime, status, created_at
    FROM bookings
    ORDER BY created_at DESC
";
$result = $mysqli->query($query);

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

// Fetch all booking details for modal
$allBookings = [];
$result2 = $mysqli->query("SELECT * FROM bookings ORDER BY created_at DESC");
while ($b = $result2->fetch_assoc()) {
    $allBookings[] = $b;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Client Reservations</title>
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

    .sidebar .logo {
      text-align: center;
      margin-bottom: 15px;
    }

    .sidebar .logo img {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      object-fit: cover;
    }

    .sidebar h4 {
      text-align: center;
      margin: 10px 0 20px;
      font-weight: bold;
    }

    .sidebar a {
      display: block;
      padding: 12px 25px;
      color: white;
      text-decoration: none;
      font-size: 15px;
      transition: all 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 8px;
    }

    .sidebar a i {
      margin-right: 10px;
    }

    .content {
      margin-left: 250px;
      padding: 30px;
    }

    .table-container {
      background: #fff;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
      margin-bottom: 20px;
      color: #6c5ce7;
      font-weight: 600;
    }

    .badge-unread {
      background-color: #ffc107;
      color: black;
    }

    .badge-read {
      background-color: #198754;
    }

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
    <a href="manage_events.php"><i class="fa fa-calendar"></i> Manage Events</a>
    <a href="reservations.php" class="active"><i class="fa fa-book"></i> New Reservations</a>
    <a href="confirmations.php"><i class="fa fa-check-circle"></i> Confirmations</a>
    <a href="reports.php"><i class="fa fa-chart-line"></i> Reports</a>
    <a href="messages.php"><i class="fa fa-comments"></i> Messages</a>
    <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="table-container">
      <h2>Client Reservations</h2>
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Client</th>
            <th>Notification</th>
            <th>Event Type</th>
            <th>Date/Time</th>
            <th>Status</th>
            <th style="width: 220px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <?php
                // ✅ Build full name (skip middlename if empty)
                $client_name = $row['firstname'];
                if (!empty($row['middlename'])) {
                    $client_name .= ' ' . $row['middlename'];
                }
                $client_name .= ' ' . $row['lastname'];
              ?>
              <tr>
                <td><?= htmlspecialchars($client_name) ?></td>
                <td>Your booking for <strong><?= htmlspecialchars($row['event_name']) ?></strong> has been submitted.</td>
                <td><?= htmlspecialchars($row['service_type']) ?></td>
                <td><?= date("M d, Y h:i A", strtotime($row['event_datetime'])) ?></td>
                <td>
                  <span class="badge <?= $row['status'] === 'Read' ? 'badge-read' : 'badge-unread' ?>">
                    <?= htmlspecialchars($row['status']) ?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-sm btn-primary mb-1" onclick="viewBooking(<?= $row['id'] ?>)"><i class="fas fa-eye"></i> View</button>
                  <?php if ($row['status'] !== 'Read'): ?>
                    <a href="reservations.php?action=read&id=<?= $row['id'] ?>" class="btn btn-sm btn-success mb-1">Mark as Read</a>
                  <?php else: ?>
                    <a href="reservations.php?action=unread&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1">Mark as Unread</a>
                  <?php endif; ?>
                  <a href="reservations.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure you want to delete this reservation?');">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center">No reservations found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
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
