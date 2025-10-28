<?php
session_start();
require 'config.php'; // database connection

// Check if user is admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle delete user request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $userId = filter_var($_POST['delete_user_id'], FILTER_VALIDATE_INT);
    
    if ($userId) {
        try {
            // First, delete all bookings associated with this user
            $stmt = $pdo->prepare("DELETE FROM bookings WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Then delete the user
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'client'");
            $stmt->execute([$userId]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = "User deleted successfully!";
                $_SESSION['msg_type'] = "success";
            } else {
                $_SESSION['message'] = "Failed to delete user or user not found.";
                $_SESSION['msg_type'] = "danger";
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "Error deleting user: " . $e->getMessage();
            $_SESSION['msg_type'] = "danger";
        }
    } else {
        $_SESSION['message'] = "Invalid user ID.";
        $_SESSION['msg_type'] = "danger";
    }
    
    header("Location: manage_user.php");
    exit();
}

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

        .menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            background: #2c3e50;
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
            height: 100vh;
            background: linear-gradient(to bottom, #2c3e50, #34495e);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px 0;
            transition: transform 0.3s ease-in-out;
            z-index: 998;
        }

        .sidebar.closed {
            transform: translateX(-100%);
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
            transition: margin-left 0.3s ease-in-out;
        }

        .content.full {
            margin-left: 0;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .content {
                margin-left: 0;
            }
            
            .table-responsive {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <!-- Menu Toggle Button -->
    <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <img src="images/logo.webp" alt="Logo">
        </div>
        <h4>Admin Panel</h4>
        <a href="dashboard_admin.php"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
        <a href="manage_user.php" class="active"><i class="fa fa-users"></i> Manage Users</a>
        <a href="manage_events.php"><i class="fa fa-calendar"></i> Manage Events</a>
        <a href="reservations.php"><i class="fa fa-book"></i> New Reservations</a>
        <a href="confirmations.php"><i class="fa fa-check-circle"></i> Confirmations</a>
        <a href="reports.php"><i class="fa fa-chart-line"></i> Reports</a>
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content" id="content">
        <h2 class="mb-3">Manage Users (Clients)</h2>
        <p>Total Clients: <strong><?= $totalUsers ?></strong></p>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
        <?php endif; ?>

        <div class="table-responsive">
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
                            <a href="admin_edit.php?type=user&id=<?= $user['id'] ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $user['id'] ?>)"><i class="fa fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            sidebar.classList.toggle('open');
            sidebar.classList.toggle('closed');
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'manage_user.php';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_user_id';
                input.value = userId;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
