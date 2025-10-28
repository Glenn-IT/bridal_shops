<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require 'config.php';

$success = false;
$error = '';
$editType = $_GET['type'] ?? 'booking'; // 'booking' or 'user'

// Handle User Edit
if ($editType === 'user' && isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        
        try {
            // Check if username or email already exists for other users
            $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
            $stmt->execute([$username, $email, $userId]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Username or email already exists for another user.";
            } else {
                // Update user
                if (!empty($password)) {
                    // Update with new password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, password=? WHERE id=? AND role='client'");
                    $stmt->execute([$username, $email, $hashedPassword, $userId]);
                } else {
                    // Update without changing password
                    $stmt = $pdo->prepare("UPDATE users SET username=?, email=? WHERE id=? AND role='client'");
                    $stmt->execute([$username, $email, $userId]);
                }
                
                if ($stmt->rowCount() > 0 || $stmt->errorCode() === '00000') {
                    $_SESSION['message'] = "User updated successfully!";
                    $_SESSION['msg_type'] = "success";
                    header('Location: manage_user.php');
                    exit();
                } else {
                    $error = "No changes were made or user not found.";
                }
            }
        } catch (PDOException $e) {
            $error = "Failed to update user: " . $e->getMessage();
        }
    }
    
    // Fetch user details
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'client'");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            $_SESSION['message'] = "User not found.";
            $_SESSION['msg_type'] = "danger";
            header('Location: manage_user.php');
            exit();
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
// Handle Booking Edit
else if ($editType === 'booking' && isset($_GET['id'])) {
    $bookingId = intval($_GET['id']);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullname = $_POST['fullname'];
        $event_name = $_POST['event_name'];
        $event_datetime = $_POST['event_datetime'];
        $location = $_POST['location'];
        $service_type = $_POST['service_type'];
        $status = $_POST['status'];

        try {
            $stmt = $pdo->prepare("UPDATE bookings SET fullname=?, event_name=?, event_datetime=?, location=?, service_type=?, status=? WHERE id=?");
            $stmt->execute([$fullname, $event_name, $event_datetime, $location, $service_type, $status, $bookingId]);

            if ($stmt->rowCount() > 0 || $pdo->errorCode() === '00000') {
                $success = true;
            } else {
                $error = "No changes were made or booking not found.";
            }
        } catch (PDOException $e) {
            $error = "Failed to update booking: " . $e->getMessage();
        }
    }

    // Fetch booking details
    try {
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$bookingId]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$booking) {
            header('Location: view_bookings.php');
            exit();
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    header('Location: ' . ($editType === 'user' ? 'manage_user.php' : 'view_bookings.php'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $editType === 'user' ? 'Edit User' : 'Edit Booking' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0">
            <i class="fas fa-<?= $editType === 'user' ? 'user-edit' : 'edit' ?>"></i>
            <?= $editType === 'user' ? 'Edit User' : 'Edit Booking' ?>
          </h4>
        </div>
        <div class="card-body">
          <?php if ($success): ?>
            <div class="alert alert-success">
              <i class="fas fa-check-circle"></i> <?= $editType === 'user' ? 'User' : 'Booking' ?> updated successfully!
            </div>
          <?php elseif ($error): ?>
            <div class="alert alert-danger">
              <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
            </div>
          <?php endif; ?>

          <?php if ($editType === 'user' && isset($user)): ?>
            <!-- User Edit Form -->
            <form method="POST">
              <div class="mb-3">
                <label class="form-label"><i class="fas fa-user"></i> Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                <small class="text-muted">Only enter a password if you want to change it</small>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Update User
                </button>
                <a href="manage_user.php" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Back to Users
                </a>
              </div>
            </form>

          <?php elseif ($editType === 'booking' && isset($booking)): ?>
            <!-- Booking Edit Form -->
            <form method="POST">
              <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($booking['fullname']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Event Name</label>
                <input type="text" name="event_name" class="form-control" value="<?= htmlspecialchars($booking['event_name']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Event Date & Time</label>
                <input type="datetime-local" name="event_datetime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($booking['event_datetime'])) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($booking['location']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Service Type</label>
                <input type="text" name="service_type" class="form-control" value="<?= htmlspecialchars($booking['service_type']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                  <option value="Pending" <?= $booking['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                  <option value="Approved" <?= $booking['status'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
                  <option value="Rejected" <?= $booking['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Update Booking
                </button>
                <a href="view_bookings.php" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Back to Bookings
                </a>
              </div>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
