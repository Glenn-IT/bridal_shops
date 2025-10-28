<?php
session_start();

// Redirect if not logged in or not a client
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit();
}

// DB connection
$conn = new mysqli("localhost", "root", "", "bridal_event_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$booking = null;

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id         = $_POST['id'];
    $fullname   = $_POST['fullname'];
    $contact    = $_POST['contact'];
    $email      = $_POST['email'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location   = $_POST['location'];

    $sql = "UPDATE bookings 
            SET fullname = ?, contact = ?, email = ?, event_name = ?, event_date = ?, event_datetime = ?, location = ? 
            WHERE id = ? AND username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssssis", $fullname, $contact, $email, $event_name, $event_date, $event_time, $location, $id, $username);
        if ($stmt->execute()) {
            echo "<script>alert('Booking updated successfully!'); window.location.href='dashboard_client.php';</script>";
            exit();
        } else {
            echo "Error executing update: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing update: " . $conn->error;
    }
}

// Fetch the latest booking for this user
$sql = "SELECT * FROM bookings WHERE username = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Error preparing fetch: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Booking</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #fdfbfb, #ebedee);
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      background: #ffffff;
      margin: 60px auto;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #6c5ce7;
    }

    label {
      font-weight: 600;
      margin-top: 15px;
      display: block;
      color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    input[type="time"] {
      width: 100%;
      padding: 12px 14px;
      margin-top: 6px;
      border-radius: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      transition: border 0.2s ease;
    }

    input:focus {
      border-color: #6c5ce7;
      outline: none;
    }

    .btn-group {
      display: flex;
      justify-content: center;
      margin-top: 30px;
    }

    button {
      padding: 12px 20px;
      font-size: 16px;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      background-color: #6c5ce7;
      color: #fff;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      opacity: 0.9;
      transform: translateY(-1px);
    }

    .no-booking {
      text-align: center;
      color: #888;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Edit Your Booking</h2>

  <?php if ($booking): ?>
  <form method="POST" action="edit_booking.php">
    <input type="hidden" name="id" value="<?= htmlspecialchars($booking['id']) ?>">

    <label>Full Name:</label>
    <input type="text" name="fullname" value="<?= htmlspecialchars($booking['fullname']) ?>" required>

    <label>Contact Number:</label>
    <input type="text" name="contact" value="<?= htmlspecialchars($booking['contact']) ?>" required>

    <label>Email Address:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($booking['email']) ?>" required>

    <label>Choose Package:</label>
    <input type="text" name="event_name" value="<?= htmlspecialchars($booking['event_name']) ?>" required>

   <label for="event_datetime">Event Date & Time</label>
    <input type="datetime-local" name="event_datetime" id="event_datetime" class="form-control" required>


    <label>Location:</label>
    <input type="text" name="location" value="<?= htmlspecialchars($booking['location']) ?>" required>

    <div class="btn-group">
      <button type="submit">Update Booking</button>
    </div>
  </form>
  <?php else: ?>
    <p class="no-booking">No previous booking found to edit.</p>
  <?php endif; ?>
</div>

</body>
</html>
