<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $bookingId = intval($_POST['id']);

    $mysqli = new mysqli("localhost", "root", "", "bridal_event_system");

    if ($mysqli->connect_errno) {
        die("Database connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("DELETE FROM bookings WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $bookingId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Booking deleted successfully.";
        } else {
            $_SESSION['message'] = "Booking not found or could not be deleted.";
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Failed to prepare delete statement.";
    }

    $mysqli->close();
} else {
    $_SESSION['message'] = "Invalid request.";
}

header("Location: manage_events.php"); // Adjust this to the correct redirect page
exit();
?>
