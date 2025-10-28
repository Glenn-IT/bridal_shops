<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_booking'])) {
    $bookingId = intval($_POST['booking_id']);
    $fullname = $_POST['fullname'];
    $service_type = $_POST['service_type'];
    $event_name = $_POST['event_name'];
    $event_datetime = $_POST['event_datetime'];
    $location = $_POST['location'];

    $username = $_SESSION['username'];

    $mysqli = new mysqli('localhost', 'root', '', 'bridal_event_system');
    if ($mysqli->connect_errno) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("UPDATE bookings SET fullname=?, service_type=?, event_name=?, event_datetime=?, location=? WHERE id=? AND username=?");
    $stmt->bind_param("sssssis", $fullname, $service_type, $event_name, $event_datetime, $location, $bookingId, $username);

    if ($stmt->execute()) {
        header("Location: book_now.php?success=1");
    } else {
        echo "Update failed: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo "Invalid request.";
}
