<?php
session_start();
require 'config.php'; // database connection



// Count total clients
$stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'client'");
$totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get all client users
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'client'");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        <a href="manage_users.php" class="active"><i class="fa fa-users"></i> Manage Users</a>
        <a href="manage_events.php"><i class="fa fa-calendar"></i> Manage Events</a>
        <a href="reservations.php"><i class="fa fa-book"></i> New Reservations</a>
        <a href="confirmations.php"><i class="fa fa-check-circle"></i> Confirmations</a>
        <a href="reports.php"><i class="fa fa-chart-line"></i> Reports</a>
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2 class="mb-3">Manage Users (Clients)</h2>
        <p>Total Clients: <strong><?= $totalUsers ?></strong></p>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
        <?php endif; ?>

        <table class="table table-bordered table-hover bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
