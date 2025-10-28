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

// ✅ Combine firstname, middlename, lastname into client_name
$result = $mysqli->query("
    SELECT id, event_name, status, 
           CONCAT(firstname, ' ', middlename, ' ', lastname) AS client_name
    FROM bookings
    ORDER BY id DESC
");
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
              <a href="view_bookings.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
