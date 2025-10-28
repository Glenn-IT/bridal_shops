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

// ✅ Fetch both 'Approved' and 'Declined' bookings
$result = $mysqli->query("SELECT service_type, event_name, firstname, middlename, lastname, event_datetime, status 
                          FROM bookings 
                          WHERE status IN ('Approved', 'Declined') 
                          ORDER BY event_datetime DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reservations Report</title>
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
    .badge-declined {
      background-color: #dc3545;
      font-size: 14px;
    }
    
    /* ✅ Filter and Sort Controls */
    .controls-section {
      margin-bottom: 20px;
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      align-items: center;
    }
    .controls-section label {
      font-weight: 600;
      margin-right: 5px;
    }
    .controls-section select {
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 14px;
    }
    .btn-print {
      background-color: #2c2f48;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      transition: 0.3s;
    }
    .btn-print:hover {
      background-color: #404269;
    }

    /* ✅ Print Styles */
    @media print {
      .sidebar, .menu-toggle, .controls-section, .no-print {
        display: none !important;
      }
      .content {
        margin-left: 0;
        padding: 0;
      }
      .report-container {
        box-shadow: none;
        padding: 20px;
      }
      body {
        background-color: white;
      }
      .print-header {
        display: block !important;
        text-align: center;
        margin-bottom: 30px;
      }
      .print-header h1 {
        font-size: 28px;
        margin-bottom: 5px;
        color: #2c2f48;
      }
      .print-header p {
        font-size: 14px;
        color: #666;
      }
    }
    
    .print-header {
      display: none;
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
    
    th {
      cursor: pointer;
      user-select: none;
    }
    th:hover {
      background-color: #495057 !important;
    }
    th.sortable::after {
      content: ' ⇅';
      opacity: 0.5;
    }
    th.sort-asc::after {
      content: ' ▲';
      opacity: 1;
    }
    th.sort-desc::after {
      content: ' ▼';
      opacity: 1;
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
  <a href="messages.php"><i class="fas fa-comments"></i> Messages</a>
  <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
    <i class="fas fa-sign-out-alt"></i> Logout (<?= htmlspecialchars($_SESSION['username']) ?>)
  </a>
</div>

<div class="content">
  <div class="report-container">
    <!-- Print Header (Only visible when printing) -->
    <div class="print-header">
      <h1>Bridal Event Management System</h1>
      <p>Reservations Report - Generated on <?= date("F d, Y") ?></p>
    </div>
    
    <h2>Reservations Report</h2>
    
    <!-- Filter and Sort Controls -->
    <div class="controls-section no-print">
      <div>
        <label for="statusFilter">Status:</label>
        <select id="statusFilter" onchange="filterTable()">
          <option value="all">All</option>
          <option value="Approved">Confirmed</option>
          <option value="Declined">Declined</option>
        </select>
      </div>
      
      <div>
        <label for="eventTypeFilter">Event Type:</label>
        <select id="eventTypeFilter" onchange="filterTable()">
          <option value="all">All Events</option>
        </select>
      </div>
      
      <button class="btn-print" onclick="window.print()">
        <i class="fas fa-print"></i> Print Report
      </button>
    </div>
    
    <table class="table table-bordered table-striped" id="reportTable">
      <thead class="table-dark">
        <tr>
          <th class="sortable" onclick="sortTable(0)">Event Type</th>
          <th class="sortable" onclick="sortTable(1)">Event Name</th>
          <th class="sortable" onclick="sortTable(2)">Client</th>
          <th class="sortable" onclick="sortTable(3)">Date / Time</th>
          <th class="sortable" onclick="sortTable(4)">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr data-status="<?= htmlspecialchars($row['status']) ?>" data-event="<?= htmlspecialchars($row['service_type']) ?>">
              <td><?= htmlspecialchars($row['service_type']) ?></td>
              <td><?= htmlspecialchars($row['event_name']) ?></td>
              <td>
                <?= htmlspecialchars($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']) ?>
              </td>
              <td data-timestamp="<?= strtotime($row['event_datetime']) ?>"><?= date("M d, Y h:i A", strtotime($row['event_datetime'])) ?></td>
              <td>
                <?php if ($row['status'] === 'Approved'): ?>
                  <span class="badge badge-confirmed">Confirmed</span>
                <?php else: ?>
                  <span class="badge badge-declined">Declined</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">No bookings found.</td>
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
  
  // Populate Event Type filter dropdown dynamically
  document.addEventListener('DOMContentLoaded', function() {
    const eventTypeFilter = document.getElementById('eventTypeFilter');
    const table = document.getElementById('reportTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    const eventTypes = new Set();
    
    // Collect unique event types
    for (let i = 0; i < rows.length; i++) {
      const eventType = rows[i].getAttribute('data-event');
      if (eventType) {
        eventTypes.add(eventType);
      }
    }
    
    // Add options to dropdown
    eventTypes.forEach(function(eventType) {
      const option = document.createElement('option');
      option.value = eventType;
      option.textContent = eventType;
      eventTypeFilter.appendChild(option);
    });
  });
  
  // Filter table based on selected filters
  function filterTable() {
    const statusFilter = document.getElementById('statusFilter').value;
    const eventTypeFilter = document.getElementById('eventTypeFilter').value;
    const table = document.getElementById('reportTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
      const row = rows[i];
      const status = row.getAttribute('data-status');
      const eventType = row.getAttribute('data-event');
      
      let showRow = true;
      
      // Check status filter
      if (statusFilter !== 'all' && status !== statusFilter) {
        showRow = false;
      }
      
      // Check event type filter
      if (eventTypeFilter !== 'all' && eventType !== eventTypeFilter) {
        showRow = false;
      }
      
      row.style.display = showRow ? '' : 'none';
    }
  }
  
  // Sort table by column
  let sortDirection = {};
  
  function sortTable(columnIndex) {
    const table = document.getElementById('reportTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr'));
    
    // Initialize sort direction for this column
    if (!sortDirection[columnIndex]) {
      sortDirection[columnIndex] = 'asc';
    } else {
      sortDirection[columnIndex] = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
    }
    
    const direction = sortDirection[columnIndex];
    
    // Remove sort classes from all headers
    const headers = table.getElementsByTagName('thead')[0].getElementsByTagName('th');
    for (let i = 0; i < headers.length; i++) {
      headers[i].classList.remove('sort-asc', 'sort-desc');
    }
    
    // Add sort class to current header
    headers[columnIndex].classList.add(direction === 'asc' ? 'sort-asc' : 'sort-desc');
    
    // Sort rows
    rows.sort(function(a, b) {
      let aValue, bValue;
      
      if (columnIndex === 3) { // Date column (now at index 3)
        aValue = parseInt(a.getElementsByTagName('td')[columnIndex].getAttribute('data-timestamp'));
        bValue = parseInt(b.getElementsByTagName('td')[columnIndex].getAttribute('data-timestamp'));
      } else {
        aValue = a.getElementsByTagName('td')[columnIndex].textContent.trim().toLowerCase();
        bValue = b.getElementsByTagName('td')[columnIndex].textContent.trim().toLowerCase();
      }
      
      if (aValue < bValue) {
        return direction === 'asc' ? -1 : 1;
      }
      if (aValue > bValue) {
        return direction === 'asc' ? 1 : -1;
      }
      return 0;
    });
    
    // Reorder table rows
    rows.forEach(function(row) {
      tbody.appendChild(row);
    });
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
