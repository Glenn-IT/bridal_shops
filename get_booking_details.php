<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo '<div class="alert alert-danger">Unauthorized access.</div>';
    exit();
}

// Get booking ID from request
$bookingId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($bookingId <= 0) {
    echo '<div class="alert alert-danger">Invalid booking ID.</div>';
    exit();
}

// Fetch booking details
$stmt = $pdo->prepare("
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
    WHERE id = ?
");
$stmt->execute([$bookingId]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    echo '<div class="alert alert-danger">Booking not found.</div>';
    exit();
}

// Verify that the booking belongs to the current user
$userStmt = $pdo->prepare("SELECT email FROM users WHERE username = ?");
$userStmt->execute([$_SESSION['username']]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['email'] !== $booking['email']) {
    echo '<div class="alert alert-danger">You do not have permission to view this booking.</div>';
    exit();
}

// Determine status badge class
$statusClass = 'bg-secondary';
switch (strtolower($booking['status'])) {
    case 'pending':
        $statusClass = 'bg-warning text-dark';
        break;
    case 'approved':
        $statusClass = 'bg-success';
        break;
    case 'confirmed':
        $statusClass = 'bg-info';
        break;
    case 'completed':
        $statusClass = 'bg-secondary';
        break;
    case 'cancelled':
        $statusClass = 'bg-danger';
        break;
}

$fullname = trim($booking['firstname'] . ' ' . $booking['middlename'] . ' ' . $booking['lastname']);
?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h6 class="text-muted mb-1">Booking ID</h6>
            <p class="fw-bold">#<?= $booking['id'] ?></p>
        </div>
        <div class="col-md-6 text-end">
            <h6 class="text-muted mb-1">Status</h6>
            <span class="badge <?= $statusClass ?> fs-6"><?= htmlspecialchars($booking['status']) ?></span>
        </div>
    </div>

    <hr>

    <div class="row mb-3">
        <div class="col-md-12">
            <h6 class="text-primary mb-3"><i class="fas fa-user"></i> Personal Information</h6>
        </div>
        <div class="col-md-6 mb-2">
            <strong>Full Name:</strong><br>
            <?= htmlspecialchars($fullname) ?>
        </div>
        <div class="col-md-6 mb-2">
            <strong>Email:</strong><br>
            <?= htmlspecialchars($booking['email']) ?>
        </div>
        <div class="col-md-6 mb-2">
            <strong>Phone Number:</strong><br>
            <?= htmlspecialchars($booking['phone_number']) ?>
        </div>
    </div>

    <hr>

    <div class="row mb-3">
        <div class="col-md-12">
            <h6 class="text-primary mb-3"><i class="fas fa-calendar-alt"></i> Event Details</h6>
        </div>
        <div class="col-md-6 mb-2">
            <strong>Event Type:</strong><br>
            <?= htmlspecialchars($booking['event_name']) ?>
        </div>
        <div class="col-md-6 mb-2">
            <strong>Service Type:</strong><br>
            <?= htmlspecialchars($booking['service_type']) ?>
        </div>
        <div class="col-md-6 mb-2">
            <strong>Package:</strong><br>
            <?= htmlspecialchars($booking['package_name'] ?? 'N/A') ?>
        </div>
        <div class="col-md-6 mb-2">
            <strong>Event Date & Time:</strong><br>
            <?= date('F d, Y h:i A', strtotime($booking['event_datetime'])) ?>
        </div>
        <div class="col-md-12 mb-2">
            <strong>Location:</strong><br>
            <?= htmlspecialchars($booking['location']) ?>
        </div>
    </div>

    <hr>

    <div class="row mb-2">
        <div class="col-md-12">
            <h6 class="text-primary mb-3"><i class="fas fa-clock"></i> Booking Information</h6>
        </div>
        <div class="col-md-6 mb-2">
            <strong>Booked On:</strong><br>
            <?= date('F d, Y h:i A', strtotime($booking['created_at'])) ?>
        </div>
    </div>

    <?php if (strtolower($booking['status']) === 'pending'): ?>
    <div class="alert alert-info mt-3">
        <i class="fas fa-info-circle"></i> Your booking is currently being reviewed by our admin team. You will receive a notification once it's processed.
    </div>
    <?php elseif (strtolower($booking['status']) === 'approved'): ?>
    <div class="alert alert-success mt-3">
        <i class="fas fa-check-circle"></i> Your booking has been approved! Please wait for confirmation details.
    </div>
    <?php elseif (strtolower($booking['status']) === 'confirmed'): ?>
    <div class="alert alert-success mt-3">
        <i class="fas fa-check-double"></i> Your booking is confirmed! We look forward to serving you.
    </div>
    <?php elseif (strtolower($booking['status']) === 'completed'): ?>
    <div class="alert alert-secondary mt-3">
        <i class="fas fa-flag-checkered"></i> This event has been completed. Thank you for choosing Mae's Bridal Shop!
    </div>
    <?php elseif (strtolower($booking['status']) === 'cancelled'): ?>
    <div class="alert alert-danger mt-3">
        <i class="fas fa-times-circle"></i> This booking has been cancelled.
    </div>
    <?php endif; ?>
</div>
