<?php
session_start();
include 'config.php';

// Check if user is logged in as client
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit();
}

// Fetch user details
$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT firstname, middlename, lastname, email FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$fullname = $user ? trim($user['firstname'] . ' ' . $user['middlename'] . ' ' . $user['lastname']) : $username;
$email = $user['email'];

// Fetch all bookings for this user's email
$bookingStmt = $pdo->prepare("
    SELECT 
        id,
        firstname,
        middlename,
        lastname,
        email,
        phone_number,
        service_type,
        package_name,
        event_name,
        event_datetime,
        location,
        status,
        created_at
    FROM bookings 
    WHERE email = ? 
    ORDER BY created_at DESC
");
$bookingStmt->execute([$email]);
$bookings = $bookingStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking History - Mae's Bridal Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Poppins', sans-serif;
            padding-top: 70px;
        }

        /* Navbar Styles */
        .navbar-custom {
            background: linear-gradient(135deg, #d6336c, #e76f51);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }

        .nav-link {
            color: white !important;
            margin: 0 10px;
        }

        .nav-link:hover {
            color: #ffd700 !important;
        }

        .btn-contact {
            background-color: #fff;
            color: #d6336c;
            border: none;
        }

        .btn-contact:hover {
            background-color: #ffd700;
            color: #d6336c;
        }

        .container {
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .page-header {
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .page-header h2 {
            margin: 0;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background: white;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #667eea;
            color: white;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 15px 10px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .badge {
            padding: 8px 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background-color: #ffc107;
            color: #000;
        }

        .status-approved {
            background-color: #28a745;
        }

        .status-confirmed {
            background-color: #17a2b8;
        }

        .status-completed {
            background-color: #6c757d;
        }

        .status-cancelled {
            background-color: #dc3545;
        }

        .btn-view {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            color: white;
        }

        .no-bookings {
            text-align: center;
            padding: 50px;
            color: #666;
        }

        .no-bookings i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ccc;
        }

        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #667eea !important;
            color: white !important;
            border: none !important;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard_client.php">Mae's Bridal Shop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background-color: white;">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="dashboard_client.php#hero">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
        <li class="nav-item"><a class="nav-link active" href="booking_history.php">Booking History</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="text-white"><?= htmlspecialchars($fullname) ?></span>
        <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        <a href="contact.php" class="btn btn-sm btn-contact">Contact Us</a>
      </div>
    </div>
  </div>
</nav>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="fas fa-history"></i> My Booking History</h2>
        <p class="mb-0">View all your past and current bookings</p>
    </div>

    <!-- Bookings Table Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-calendar-check"></i> All Bookings</h5>
        </div>
        <div class="card-body">
            <?php if (count($bookings) > 0): ?>
                <div class="table-responsive">
                    <table id="bookingsTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking ID</th>
                                <th>Event Type</th>
                                <th>Package</th>
                                <th>Event Date</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Booked On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 1;
                            foreach ($bookings as $booking): 
                                // Determine status badge class
                                $statusClass = 'status-' . strtolower($booking['status']);
                            ?>
                            <tr>
                                <td><?= $count++ ?></td>
                                <td><strong>#<?= $booking['id'] ?></strong></td>
                                <td><?= htmlspecialchars($booking['event_name']) ?></td>
                                <td><?= htmlspecialchars($booking['package_name'] ?? 'N/A') ?></td>
                                <td><?= date('M d, Y h:i A', strtotime($booking['event_datetime'])) ?></td>
                                <td><?= htmlspecialchars($booking['location']) ?></td>
                                <td>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= htmlspecialchars($booking['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($booking['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-view btn-sm" onclick="viewBooking(<?= $booking['id'] ?>)">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-bookings">
                    <i class="fas fa-calendar-times"></i>
                    <h4>No Bookings Yet</h4>
                    <p>You haven't made any bookings yet. Start by browsing our services!</p>
                    <a href="services.php" class="btn btn-view mt-3">
                        <i class="fas fa-search"></i> Browse Services
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h5 class="modal-title" id="bookingModalLabel"><i class="fas fa-info-circle"></i> Booking Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="bookingDetails">
        <!-- Booking details will be loaded here -->
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#bookingsTable').DataTable({
        pageLength: 10,
        order: [[0, 'desc']], // Sort by booking ID descending
        language: {
            search: "Search bookings:",
            lengthMenu: "Show _MENU_ bookings per page",
            info: "Showing _START_ to _END_ of _TOTAL_ bookings",
            infoEmpty: "No bookings available",
            infoFiltered: "(filtered from _MAX_ total bookings)"
        }
    });
});

function viewBooking(bookingId) {
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
    modal.show();
    
    // Fetch booking details via AJAX
    fetch('get_booking_details.php?id=' + bookingId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('bookingDetails').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('bookingDetails').innerHTML = 
                '<div class="alert alert-danger">Error loading booking details.</div>';
        });
}
</script>

</body>
</html>
