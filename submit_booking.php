<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer

$mysqli = new mysqli("localhost", "root", "", "bridal_event_system");
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}


$firstname      = $mysqli->real_escape_string($_POST['firstname']);
$middlename     = $mysqli->real_escape_string($_POST['middlename']);
$lastname       = $mysqli->real_escape_string($_POST['lastname']);
$email          = $mysqli->real_escape_string($_POST['email']);
$phone_number   = $mysqli->real_escape_string($_POST['phone_number']);
$service_type   = $mysqli->real_escape_string($_POST['service_type']);
$package_name   = $mysqli->real_escape_string($_POST['package_name'] ?? '');
$event_name     = $mysqli->real_escape_string($_POST['event_name']);
$event_datetime = $mysqli->real_escape_string($_POST['event_datetime']);
$location       = $mysqli->real_escape_string($_POST['location']);
$payment_method = $mysqli->real_escape_string($_POST['payment_method'] ?? 'Cash');
$status         = "Pending";

// Handle file upload for GCash payment
$payment_screenshot = null;
if ($payment_method === 'GCash' && isset($_FILES['payment_screenshot']) && $_FILES['payment_screenshot']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/payment_references/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($_FILES['payment_screenshot']['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($file_extension, $allowed_extensions)) {
        $new_filename = 'payment_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        
        if (move_uploaded_file($_FILES['payment_screenshot']['tmp_name'], $upload_path)) {
            $payment_screenshot = $upload_path;
        }
    }
}


$check = $mysqli->prepare("SELECT id FROM bookings WHERE event_datetime = ?");
$check->bind_param("s", $event_datetime);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    // ❌ Duplicate datetime found
    $_SESSION['error'] = "This date and time is already booked. Please choose a different slot.";
    header("Location: dashboard_client.php#booknow");
    exit();
}
$check->close();

$stmt = $mysqli->prepare("INSERT INTO bookings 
    (firstname, middlename, lastname, email, phone_number, service_type, package_name, event_name, event_datetime, location, payment_method, payment_screenshot, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssssss", 
    $firstname, $middlename, $lastname, 
    $email, $phone_number, $service_type, $package_name,
    $event_name, $event_datetime, $location, $payment_method, $payment_screenshot, $status
);

if ($stmt->execute()) {
    // Build full name
    $fullname = trim("$firstname $middlename $lastname");

    
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ityourboiaki@gmail.com'; // your Gmail
        $mail->Password   = 'fojt zvoj imdr xwnw';    // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('delacruzmelody847@gmail.com', 'Bridal Event System');
        $mail->addAddress($email, $fullname);

        $mail->isHTML(true);
        $mail->Subject = 'Booking Received';
        $packageInfo = !empty($package_name) ? "<br><strong>Package:</strong> $package_name" : "";
        $mail->Body    = "Hello $fullname,<br><br>
                          Your reservation for <b>$event_name</b> on <b>$event_datetime</b> has been received.<br>
                          <strong>Service Type:</strong> $service_type$packageInfo<br><br>
                          We will confirm soon.<br><br>Thank you!";
        $mail->send();
    } catch (Exception $e) {
        error_log("Email error: " . $mail->ErrorInfo);
    }

    $_SESSION['success'] = "Reservation submitted successfully!";
    header("Location: dashboard_client.php#booknow");
    exit();
} else {
    die("Error: " . $stmt->error);
}
?>
