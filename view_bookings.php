<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include_once 'config.php'; // Uses $mysqli from config

// Fetch all bookings
$bookings = $mysqli->query("SELECT id, firstname, middlename, lastname, event_name, event_datetime, service_type, status FROM bookings ORDER BY created_at DESC");

// Encode all bookings as JSON for the modal
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
  <title>Manage Bookings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }

    /* Sidebar */
    .sidebar {
      width: 250px; height: 100vh; position: fixed; top: 0; left: 0;
      background-color: #2c2f48; padding-top: 20px; color: #fff; transition: all 0.3s ease;
    }
    .sidebar img { display: block; margin: 0 auto 15px auto; width: 80px; border-radius: 50%; }
    .sidebar h2 { text-align: center; font-size: 20px; font-weight: 700; margin-bottom: 25px; color: #fff; }
    .sidebar a {
      display: flex; align-items: center; gap: 10px; color: #d1d1e0;
      padding: 12px 20px; text-decoration: none; transition: 0.3s;
      border-radius: 8px; margin: 5px 10px; font-size: 15px;
    }
    .sidebar a:hover, .sidebar a.active { background-color: #404269; color: #fff; }
    .sidebar a i { font-size: 18px; }

    /* Content */
    .content { margin-left: 250px; padding: 30px; }
    .page-card {
      background: #fff; border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 30px;
    }
    .page-card h2 { text-align: center; color: #2c2f48; font-weight: 700; margin-bottom: 25px; }

    /* Modal detail rows */
    .detail-row { display: flex; gap: 10px; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { font-weight: 600; color: #2c2f48; min-width: 145px; }
    .detail-value { color: #444; }

    /* Responsive */
    @media (max-width: 992px) {
      .sidebar { left: -250px; }
      .sidebar.active { left: 0; }
      .content { margin-left: 0; }
      .menu-toggle { display: block !important; }
    }
    .menu-toggle {
      display: none; position: fixed; top: 15px; left: 15px;
      background: #2c2f48; color: #fff; border: none;
      padding: 10px 12px; border-radius: 6px; z-index: 1100;
    }
  </style>
</head>
<body>

<button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
  <i class="fas fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">
  <img src="images/logo.webp" alt="Logo">
  <h2>Admin Panel</h2>
  <a href="dashboard_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="manage_user.php"><i class="fas fa-users"></i> Manage Users</a>
  <a href="manage_events.php"><i class="fas fa-calendar-alt"></i> Manage Events</a>
  <a href="reservations.php"><i class="fas fa-book"></i> New Reservations</a>
  <a href="confirmations.php"><i class="fas fa-check-circle"></i> Confirmations</a>
  <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
  <a href="messages.php"><i class="fas fa-comments"></i> Messages</a>
  <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
    <i class="fas fa-sign-out-alt"></i> Logout (<?= htmlspecialchars($_SESSION['username']) ?>)
  </a>
</div>

<div class="content">
  <div class="page-card">
    <h2><i class="fas fa-calendar-check me-2"></i>Event Booking Management</h2>

    <table class="table table-bordered table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Event Name</th>
          <th>Client Name</th>
          <th>Service Type</th>
          <th>Date</th>
          <th>Status</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $counter = 1; while ($row = $bookings->fetch_assoc()): ?>
          <?php
            $statusColor = match($row['status']) {
              'Approved'  => 'success',
              'Declined'  => 'danger',
              'Rejected'  => 'danger',
              default     => 'warning'
            };
            $fullname = trim($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']);
          ?>
          <tr>
            <td><?= $counter++ ?></td>
            <td><?= htmlspecialchars($row['event_name']) ?></td>
            <td><?= htmlspecialchars($fullname) ?></td>
            <td><?= htmlspecialchars($row['service_type']) ?></td>
            <td><?= date('M d, Y g:i A', strtotime($row['event_datetime'])) ?></td>
            <td><span class="badge bg-<?= $statusColor ?>"><?= htmlspecialchars($row['status']) ?></span></td>
            <td class="text-center">
              <button class="btn btn-sm btn-info text-white"
                onclick="viewBooking(<?= $row['id'] ?>)">
                <i class="fas fa-eye"></i> View
              </button>
              <a href="admin_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-edit"></i> Edit
              </a>
              <form method="POST" action="delete_booking.php" class="d-inline"
                onsubmit="return confirm('Are you sure you want to delete this booking?');">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger">
                  <i class="fas fa-trash"></i> Delete
                </button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#2c2f48; color:#fff;">
        <h5 class="modal-title" id="bookingModalLabel">
          <i class="fas fa-calendar-check me-2"></i> Booking Details
        </h5>
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

<script>
  const bookings = <?= json_encode($allBookings) ?>;

  function viewBooking(id) {
    const b = bookings.find(x => x.id == id);
    if (!b) return;

    const fullname = [b.firstname, b.middlename, b.lastname].filter(Boolean).join(' ');

    const statusColors = { Approved: 'success', Declined: 'danger', Rejected: 'danger', Pending: 'warning' };
    const color = statusColors[b.status] || 'secondary';

    document.getElementById('m-name').textContent     = fullname;
    document.getElementById('m-email').textContent    = b.email || '—';
    document.getElementById('m-phone').textContent    = b.phone_number || '—';
    document.getElementById('m-service').textContent  = b.service_type;
    document.getElementById('m-event').textContent    = b.event_name;
    document.getElementById('m-datetime').textContent = formatDate(b.event_datetime);
    document.getElementById('m-location').textContent = b.location || '—';
    document.getElementById('m-created').textContent  = formatDate(b.created_at);
    document.getElementById('m-status').innerHTML     =
      `<span class="badge bg-${color}">${b.status}</span>`;
    document.getElementById('m-edit-btn').href        = `admin_edit.php?id=${b.id}`;

    new bootstrap.Modal(document.getElementById('bookingModal')).show();
  }

  function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr.replace(' ', 'T'));
    return d.toLocaleString('en-US', {
      year: 'numeric', month: 'long', day: 'numeric',
      hour: 'numeric', minute: '2-digit', hour12: true
    });
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
