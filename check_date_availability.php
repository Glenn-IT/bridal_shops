<?php
session_start();

// Allow guests to check date availability
// No authentication check needed here - just checking dates

header('Content-Type: application/json');

// Database connection
$mysqli = new mysqli("localhost", "root", "", "bridal_event_system");
if ($mysqli->connect_errno) {
    echo json_encode(['available' => false, 'error' => 'Database connection failed']);
    exit();
}

// Get the date from the request
$event_datetime = isset($_GET['event_datetime']) ? $_GET['event_datetime'] : '';

if (empty($event_datetime)) {
    echo json_encode(['available' => true, 'message' => 'No date provided']);
    exit();
}

// Extract just the date part (YYYY-MM-DD)
$event_date = date('Y-m-d', strtotime($event_datetime));

// Check if any booking exists on this date
$stmt = $mysqli->prepare("SELECT DATE(event_datetime) as booking_date FROM bookings WHERE DATE(event_datetime) = ? LIMIT 1");
$stmt->bind_param("s", $event_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        'available' => false, 
        'message' => 'This date is already booked. Please choose another date.'
    ]);
} else {
    echo json_encode([
        'available' => true, 
        'message' => 'This date is available.'
    ]);
}

$stmt->close();
$mysqli->close();
?>
