<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

try {
   $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'client'");
$totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM bookings");
    $totalEvents = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM bookings");
    $totalReservations = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM bookings WHERE status = 'pending'");
    $pendingConfirmations = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM bookings WHERE status = 'pending'");
    $unreadNotifications = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --bg: #f4f6f9;
      --text: #2f3542;
      --card-bg: #fff;
      --sidebar-bg: linear-gradient(135deg, #1e272e, #34495e);
      --sidebar-text: #ecf0f1;
    }

    body.dark {
      --bg: #18191a;
      --text: #f1f2f6;
      --card-bg: #242526;
      --sidebar-bg: linear-gradient(135deg, #111, #222);
      --sidebar-text: #ccc;
    }

    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
      transition: background 0.3s, color 0.3s;
    }

    body {
      margin: 0;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      overflow-x: hidden;
    }

    .menu-toggle {
      display: none;
      position: absolute;
      top: 1rem;
      left: 1rem;
      background: #3498db;
      color: white;
      border: none;
      padding: 10px 15px;
      font-size: 18px;
      cursor: pointer;
      z-index: 999;
      border-radius: 6px;
    }

    .sidebar {
      width: 250px;
      background: var(--sidebar-bg);
      color: var(--sidebar-text);
      padding: 2rem 1rem;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      transform: translateX(0);
      transition: transform 0.3s ease-in-out;
      z-index: 998;
      box-shadow: 4px 0 12px rgba(0,0,0,0.2);
    }

    .sidebar.closed {
      transform: translateX(-100%);
    }

    .sidebar img {
      display: block;
      margin: 0 auto 1rem;
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      background-color: #fff;
      padding: 6px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 2rem;
      font-size: 24px;
      font-weight: 600;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      color: var(--sidebar-text);
      padding: 12px 16px;
      border-radius: 10px;
      text-decoration: none;
      margin-bottom: 10px;
      font-weight: 500;
      position: relative;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
    }

    .main {
      margin-left: 250px;
      padding: 2rem;
      transition: margin-left 0.3s ease-in-out;
    }

    .main.full {
      margin-left: 0;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .clock {
      font-weight: bold;
      font-size: 16px;
    }

    .toggle-btn {
      background: #3498db;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
    }

    .dashboard-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1.5rem;
      margin-top: 2rem;
    }

    .card {
      background: var(--card-bg);
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
      color: var(--text);
    }

    .card i {
      font-size: 36px;
      margin-bottom: 10px;
    }

    .card h3 {
      margin: 0;
      font-size: 2.2rem;
    }

    .card p {
      margin: 0.5rem 0 0;
      font-weight: 500;
    }

    .red { background-color: #e74c3c; color: white; }
    .blue { background-color: #3498db; color: white; }
    .yellow { background-color: #f1c40f; color: #2c3e50; }
    .green { background-color: #2ecc71; color: white; }
    .purple { background-color: #8e44ad; color: white; }

    @media (max-width: 768px) {
      .menu-toggle { display: block; }
      .main { margin-left: 0; }
    }
  </style>
</head>
<body>
  <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>

  <div class="sidebar" id="sidebar">
    <img src="images/logo.webp" alt="Logo">
    <h2>Admin Panel</h2>
    <a href="dashboard_admin.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="manage_user.php"><i class="fas fa-users"></i> Manage Users</a>
    <a href="manage_events.php"><i class="fas fa-calendar-alt"></i> Manage Events</a>
    <a href="reservations.php"><i class="fas fa-book"></i> New Reservations</a>
    <a href="confirmations.php"><i class="fas fa-check-circle"></i> Confirmations</a>
    <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
    <a href="#" onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main" id="main">
    <div class="topbar">
      <h1>Welcome, Admin</h1>
      <div>
        <span class="clock" id="clock"></span>
        <button class="toggle-btn" onclick="toggleDarkMode()">Toggle Dark Mode</button>
      </div>
    </div>

    <div class="dashboard-cards">
      <div class="card purple">
        <i class="fas fa-user"></i>
        <h3><?= htmlspecialchars($totalUsers) ?></h3>
        <p>Total Users</p>
      </div>

      <div class="card red">
        <i class="fas fa-calendar-check"></i>
        <h3><?= htmlspecialchars($totalEvents) ?></h3>
        <p>Total Events</p>
      </div>

      <div class="card blue">
        <i class="fas fa-users"></i>
        <h3><?= htmlspecialchars($totalReservations) ?></h3>
        <p>Total Reservations</p>
      </div>

      <div class="card yellow">
        <i class="fas fa-clock"></i>
        <h3><?= htmlspecialchars($pendingConfirmations) ?></h3>
        <p>Pending Confirmations</p>
      </div>

      <div class="card green">
        <i class="fas fa-bell"></i>
        <h3><?= htmlspecialchars($unreadNotifications) ?></h3>
        <p>Unread Notifications</p>
      </div>
    </div>
  </div>

  <script>
    function toggleDarkMode() {
      document.body.classList.toggle('dark');
    }

    function updateClock() {
      const clock = document.getElementById('clock');
      const now = new Date();
      clock.textContent = now.toLocaleTimeString();
    }

    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('closed');
      document.getElementById('main').classList.toggle('full');
    }

    function confirmLogout() {
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    }

    setInterval(updateClock, 1000);
    updateClock();
  </script>
</body>
</html>
