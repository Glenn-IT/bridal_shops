<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from database
$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT firstname, middlename, lastname, phone_number, email FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    // If user not found, logout
    header("Location: logout.php");
    exit();
}

$firstname = $user['firstname'] ?? '';
$middlename = $user['middlename'] ?? '';
$lastname = $user['lastname'] ?? '';
$phone_number = $user['phone_number'] ?? '';
$email = $user['email'] ?? '';
$fullname = trim("$firstname $middlename $lastname");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mae’s Bridal Shop - Client Homepage</title>

  <!-- CSS Links -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins&display=swap" rel="stylesheet" />

  <style>
    html {
      scroll-behavior: smooth;
    }
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    /* Navbar */
    .navbar-custom {
      background-color: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .navbar-brand {
      font-weight: bold;
      font-size: 1.4rem;
      color: #e76f51 !important;
    }
    .nav-link {
      font-weight: 500;
      color: #333 !important;
    }
    .nav-link:hover {
      color: #e76f51 !important;
    }
    .btn-contact {
      background-color: #e76f51;
      color: white;
      border: none;
    }
    .btn-contact:hover {
      background-color: #d45b3b;
    }

    /* Hero section */
    .hero-section {
      min-height: 100vh;
      background: url('bg.jpeg') center center/cover no-repeat;
      position: relative;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 3rem 1rem;
    }
    .hero-overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.55);
      z-index: 1;
    }
    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
    }
    .hero-content h1 {
      font-size: 3rem;
      font-family: 'Playfair Display', serif;
      font-weight: 700;
    }

    .book-now-section {
      min-height: 100vh;
      padding: 5rem 1rem;
      background-color: #e9ecef; /* soft gray background */
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .book-now-card {
      width: 100%;
      max-width: 650px;
      background: #fdfdfd;
      padding: 2.5rem;
      border-radius: 20px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .book-now-card h2 {
      text-align: center;
      color: #495057;
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      margin-bottom: 30px;
    }
    label {
      font-weight: 500;
      margin-top: 12px;
      color: #555;
    }
    .form-control, .form-select {
      border-radius: 10px;
      padding: 10px 12px;
      border: 1px solid #ced4da;
      transition: 0.3s;
    }
    .form-control:focus, .form-select:focus {
      border-color: #6c5ce7;
      box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
    }
    .btn-action {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      border-radius: 10px;
      transition: 0.3s;
    }
    .btn-primary {
      background-color: #6c5ce7;
      border: none;
    }
    .btn-primary:hover {
      background-color: #5a4bd8;
    }
    .btn-secondary {
      background-color: #adb5bd;
      border: none;
    }
    .btn-secondary:hover {
      background-color: #868e96;
    }
    .btn-group-action {
      display: flex;
      flex-direction: column;
      gap: 14px;
      margin-top: 30px;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 1.5rem;
      background-color: #f1f3f5;
      font-size: 0.9rem;
      color: #666;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#hero">Mae’s Bridal Shop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="#hero">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="#booknow">Book Now</a></li>
        <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
        <li class="nav-item"><a class="nav-link" href="booking_history.php">Booking History</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="text-muted"><?= htmlspecialchars($fullname) ?></span>
        <a href="logout.php" class="btn btn-sm btn-outline-secondary">Logout</a>
        <a href="contact.php" class="btn btn-sm btn-contact">Contact Us</a>
      </div>
    </div>
  </div>
</nav>

<!-- Hero -->
<div id="hero" class="hero-section">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="text-uppercase mb-2">Capturing Moments Creating Memories</p>
    <h1>Making Every Smile, Every Laugh, and Every Tear Timeless</h1>
    <p>At Mae’s Bridal Shop, we turn fleeting moments into forever memories. From weddings and birthdays to personal milestones, we capture your story with passion and elegance.</p>
  </div>
</div>

<!-- Book Now Section -->
<div id="booknow" class="book-now-section">
  <div class="book-now-card">
    <h2>Book a Reservation</h2>
    
    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <form action="submit_booking.php" method="POST" enctype="multipart/form-data">
      <!-- Hidden fields for user information -->
      <input type="hidden" name="firstname" value="<?= htmlspecialchars($firstname) ?>">
      <input type="hidden" name="middlename" value="<?= htmlspecialchars($middlename) ?>">
      <input type="hidden" name="lastname" value="<?= htmlspecialchars($lastname) ?>">
      <input type="hidden" name="phone_number" value="<?= htmlspecialchars($phone_number) ?>">
      <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

      <div class="mb-3">
        <label for="service_type" class="form-label">Service Type</label>
        <select name="service_type" id="service_type" class="form-select" required>
          <option value="">-- Select Gown Type --</option>
          <option value="Wedding" data-event="Wedding">Wedding Gown</option>
          <option value="Birthday" data-event="Birthday">Birthday Gown</option>
          <option value="Anniversary" data-event="Anniversary">Anniversary Gown</option>
          <option value="Corporate" data-event="Corporate">Corporate Gown</option>
        </select>
      </div>

      <div class="mb-3" id="package_container" style="display: none;">
        <label for="package_name" class="form-label">Select Package</label>
        <select name="package_name" id="package_name" class="form-select">
          <option value="">-- Select Package --</option>
        </select>
        <div id="package_details" class="mt-2 p-3" style="background-color: #f8f9fa; border-radius: 8px; display: none;">
          <p class="mb-1"><strong>Description:</strong> <span id="package_description"></span></p>
          <p class="mb-0"><strong>Price:</strong> ₱<span id="package_price"></span></p>
        </div>
      </div>

      <div class="mb-3">
        <label for="event_name" class="form-label">Event Name</label>
        <input type="text" name="event_name" id="event_name" class="form-control" placeholder="e.g. Jane's Wedding" required>
      </div>

      <div class="mb-3">
        <label for="event_datetime" class="form-label">Event Date & Time</label>
        <input type="datetime-local" name="event_datetime" id="event_datetime" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="location" class="form-label">Event Location</label>
        <textarea name="location" id="location" class="form-control" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label for="payment_method" class="form-label">Payment Method After Event</label>
        <select name="payment_method" id="payment_method" class="form-select" required>
          <option value="">-- Select Payment Method --</option>
          <option value="Cash">Cash</option>
          <option value="GCash">GCash</option>
        </select>
      </div>

      <div class="mb-3" id="gcash_details" style="display: none;">
        <div class="text-center mb-3">
          <img src="images/gcash_qr.png" alt="GCash QR Code" style="max-width: 300px; width: 100%; border: 2px solid #007bff; border-radius: 10px; padding: 10px; background: white;">
        </div>
        <label for="payment_screenshot" class="form-label">Upload Screenshot of Reference</label>
        <input type="file" name="payment_screenshot" id="payment_screenshot" class="form-control" accept="image/*">
      </div>

      <div class="btn-group-action">
        <button type="submit" name="submit_booking" class="btn btn-primary btn-action">Submit Reservation</button>
        <a href="#hero" class="btn btn-secondary btn-action">Back to Top</a>
      </div>
    </form>
  </div>
</div>

<!-- Footer -->
<footer>
  &copy; <?= date('Y') ?> Mae’s Bridal Shop. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Service Type change event - Load packages dynamically
document.getElementById('service_type').addEventListener('change', function() {
  const eventType = this.value;
  const packageContainer = document.getElementById('package_container');
  const packageSelect = document.getElementById('package_name');
  const packageDetails = document.getElementById('package_details');
  
  // Reset package selection
  packageSelect.innerHTML = '<option value="">-- Select Package --</option>';
  packageDetails.style.display = 'none';
  
  if (eventType === '') {
    packageContainer.style.display = 'none';
    packageSelect.removeAttribute('required');
    return;
  }
  
  // Show package container
  packageContainer.style.display = 'block';
  packageSelect.setAttribute('required', 'required');
  
  // Fetch packages from server
  fetch(`fetch_packages.php?event=${encodeURIComponent(eventType)}`)
    .then(response => response.json())
    .then(packages => {
      if (packages.length > 0) {
        packages.forEach(pkg => {
          const option = document.createElement('option');
          option.value = pkg.package_name;
          option.textContent = `${pkg.package_name} - ₱${parseFloat(pkg.price).toLocaleString()}`;
          option.dataset.description = pkg.description;
          option.dataset.price = pkg.price;
          packageSelect.appendChild(option);
        });
      } else {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'No packages available';
        option.disabled = true;
        packageSelect.appendChild(option);
      }
    })
    .catch(error => {
      console.error('Error fetching packages:', error);
      alert('Failed to load packages. Please try again.');
    });
});

// Package selection change event - Show package details
document.getElementById('package_name').addEventListener('change', function() {
  const selectedOption = this.options[this.selectedIndex];
  const packageDetails = document.getElementById('package_details');
  const packageDescription = document.getElementById('package_description');
  const packagePrice = document.getElementById('package_price');
  
  if (this.value === '') {
    packageDetails.style.display = 'none';
    return;
  }
  
  // Display package details
  packageDescription.textContent = selectedOption.dataset.description || 'No description available';
  packagePrice.textContent = parseFloat(selectedOption.dataset.price).toLocaleString();
  packageDetails.style.display = 'block';
});

// Payment method change event - Show/hide GCash details
document.getElementById('payment_method').addEventListener('change', function() {
  const gcashDetails = document.getElementById('gcash_details');
  const paymentScreenshot = document.getElementById('payment_screenshot');
  
  if (this.value === 'GCash') {
    gcashDetails.style.display = 'block';
    paymentScreenshot.setAttribute('required', 'required');
  } else {
    gcashDetails.style.display = 'none';
    paymentScreenshot.removeAttribute('required');
    paymentScreenshot.value = '';
  }
});
</script>
</body>
</html>
