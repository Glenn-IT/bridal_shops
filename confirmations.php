<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$mysqli = new mysqli("localhost", "root", "", "bridal_event_system");
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Email function
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ityourboiaki@gmail.com';
        $mail->Password   = 'fojt zvoj imdr xwnw'; // app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('delacruzmelody847@gmail.com', 'Bridal Event System');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
    }
}

// Dummy SMS function
function sendSMS($phone, $message) {
    $apiUrl = "https://api.smsprovider.com/send";
    $apiKey = "YOUR_API_KEY";
    $postData = [
        'to' => $phone,
        'message' => $message,
        'apikey' => $apiKey
    ];
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// Handle approve/decline/delete
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $resultUser = $mysqli->query("SELECT firstname, middlename, lastname, email, phone_number FROM bookings WHERE id=$id");
    if ($resultUser && $resultUser->num_rows > 0) {
        $userRow = $resultUser->fetch_assoc();

        // Build full name
        $names = array_filter([$userRow['firstname'], $userRow['middlename'], $userRow['lastname']]);
        $fullname = !empty($names) ? implode(" ", $names) : 'Unknown Client';

        $email = $userRow['email'] ?? '';
        $phone = $userRow['phone_number'] ?? '';

        if ($_GET['action'] === 'approve') {
            $mysqli->query("UPDATE bookings SET status='Approved' WHERE id=$id");
            $msg = "Hello $fullname, your booking reservation has been approved.";
            $mysqli->query("INSERT INTO notifications (username, message) VALUES ('" . $mysqli->real_escape_string($fullname) . "', '" . $mysqli->real_escape_string($msg) . "')");
            if ($email) sendEmail($email, "Booking Approved", $msg);
            if ($phone) sendSMS($phone, $msg);

        } elseif ($_GET['action'] === 'decline') {
            $mysqli->query("UPDATE bookings SET status='Declined' WHERE id=$id");
            $msg = "Hello $fullname, your booking reservation has been declined.";
            $mysqli->query("INSERT INTO notifications (username, message) VALUES ('" . $mysqli->real_escape_string($fullname) . "', '" . $mysqli->real_escape_string($msg) . "')");
            if ($email) sendEmail($email, "Booking Declined", $msg);
            if ($phone) sendSMS($phone, $msg);

        } elseif ($_GET['action'] === 'delete') {
            $mysqli->query("DELETE FROM bookings WHERE id=$id");
        }
    }
    header("Location: confirmations.php");
    exit();
}

// Fetch all bookings
$query = "SELECT id, firstname, middlename, lastname, email, phone_number, event_name, service_type, event_datetime, status 
          FROM bookings ORDER BY created_at DESC";
$result = $mysqli->query($query);
if (!$result) {
    die("Query failed: " . $mysqli->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reservation Confirmations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 250px;
      background: #212529;
      color: #fff;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      padding-top: 20px;
    }
    .sidebar .logo {
      text-align: center;
      margin-bottom: 30px;
    }
    .sidebar .logo img {
      max-width: 100px;
      border-radius: 50%;
    }
    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: #ddd;
      text-decoration: none;
      font-size: 15px;
    }
    .sidebar a:hover,
    .sidebar a.active {
      background: #495057;
      color: #fff;
    }
    .sidebar i {
      margin-right: 10px;
    }
    .content {
      margin-left: 250px;
      padding: 30px;
      width: 100%;
      background: #f8f9fa;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="logo">
    <img src="images/logo.webp" alt="Logo">
    <h5 class="mt-2">Admin Panel</h5>
  </div>
  <a href="dashboard_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="manage_users.php"><i class="fa fa-users"></i> Manage Users</a>
  <a href="manage_events.php"><i class="fa fa-calendar"></i> Manage Events</a>
  <a href="reservations.php"><i class="fa fa-book"></i> New Reservations</a>
  <a href="confirmations.php" class="active"><i class="fas fa-envelope-open-text"></i> Confirmations</a>
  <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Content -->
<div class="content">
  <div class="container-fluid bg-white p-4 rounded shadow-sm">
    <h2 class="text-primary mb-4"><i class="fas fa-envelope-open-text"></i> Reservation Confirmations</h2>
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Client Name</th>
          <th>Email</th>
          <th>Phone Number</th>
          <th>Event</th>
          <th>Date/Time</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php 
            $names = array_filter([$row['firstname'], $row['middlename'], $row['lastname']]);
            $fullname = !empty($names) ? implode(" ", $names) : 'Unknown Client';
          ?>
          <tr>
            <td><?= htmlspecialchars($fullname) ?></td>
            <td><?= htmlspecialchars($row['email'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['phone_number'] ?? 'N/A') ?></td>
            <td>
              <?= htmlspecialchars($row['event_name']) ?><br>
              <small class="text-muted"><?= htmlspecialchars($row['service_type']) ?></small>
            </td>
            <td><?= date("M d, Y h:i A", strtotime($row['event_datetime'])) ?></td>
            <td>
              <span class="badge <?= 
                $row['status'] === 'Approved' ? 'bg-success' : 
                ($row['status'] === 'Declined' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                <?= $row['status'] ?>
              </span>
            </td>
            <td>
              <?php if ($row['status'] === 'Pending'): ?>
                <a href="?action=approve&id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Approve</a>
                <a href="?action=decline&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Decline</a>
              <?php endif; ?>
              <a href="?action=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this reservation?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
