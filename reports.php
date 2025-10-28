<?php
session_start();

// ✅ Only admin can access
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ✅ Database connection
$mysqli = new mysqli("localhost", "root", "", "bridal_event_system");
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// ✅ Fetch only 'Approved' bookings
$result = $mysqli->query("SELECT event_name, firstname, middlename, lastname, event_datetime 
                          FROM bookings 
                          WHERE status = 'Approved' 
                          ORDER BY event_datetime DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Confirmed Reservations Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 0;
    }

    /* ✅ Sidebar Styling */
    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #2c2f48; /* Dark navy purple */
      padding-top: 20px;
      color: #fff;
      transition: all 0.3s ease;
    }
    .sidebar img {
      display: block;
      margin: 0 auto 15px auto;
      width: 80px;
      border-radius: 50%;
    }
    .sidebar h2 {
      text-align: center;
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 25px;
      color: #fff;
    }
    .sidebar a {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #d1d1e0;
      padding: 12px 20px;
      text-decoration: none;
      transition: 0.3s;
      border-radius: 8px;
      margin: 5px 10px;
      font-size: 15px;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #404269; /* Hover highlight */
      color: #fff;
    }
    .sidebar a i {
      font-size: 18px;
    }

    /* ✅ Content */
    .content {
      margin-left: 250px;
      padding: 30px;
    }
    .report-container {
      background-color: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #2c2f48;
      margin-bottom: 25px;
      font-weight: 700;
    }
    .badge-confirmed {
      background-color: #28a745;
      font-size: 14px;
    }

    /* ✅ Responsive Sidebar */
    @media (max-width: 992px) {
      .sidebar {
        left: -250px;
      }
      .sidebar.active {
        left: 0;
      }
      .content {
        margin-left: 0;
      }
      .menu-toggle {
        display: block;
        position: fixed;
        top: 15px;
        left: 15px;
        background: #2c2f48;
        color: #fff;
        border: none;
        padding: 10px 12px;
        border-radius: 6px;
        z-index: 1000;
      }
    }
    .menu-toggle {
      display: none;
    }
  </style>
</head>
<body>

<button class="menu-toggle" onclick="toggleSidebar()">
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
  <a href="reports.php" class="active"><i class="fas fa-chart-line"></i> Reports</a>
  <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
    <i class="fas fa-sign-out-alt"></i> Logout (<?= htmlspecialchars($_SESSION['username']) ?>)
  </a>
</div>

<div class="content">
  <div class="report-container">
    <h2>Confirmed Reservations Report</h2>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Event Name</th>
          <th>Client</th>
          <th>Date / Time</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['event_name']) ?></td>
              <td>
                <?= htmlspecialchars($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']) ?>
              </td>
              <td><?= date("M d, Y h:i A", strtotime($row['event_datetime'])) ?></td>
              <td><span class="badge badge-confirmed">Confirmed</span></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center">No confirmed bookings found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("active");
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
