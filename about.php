<?php
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['username']) && isset($_SESSION['role']);
$role = $isLoggedIn ? $_SESSION['role'] : '';
$fullname = '';

if ($isLoggedIn) {
    include 'config.php';
    $stmt = $pdo->prepare("SELECT firstname, middlename, lastname FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $fullname = trim($user['firstname'] . ' ' . $user['middlename'] . ' ' . $user['lastname']);
    } else {
        $fullname = $_SESSION['username'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Mae's Bridal Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fdfdfd;
            color: #333;
            line-height: 1.6;
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

        .page-header {
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: white;
            padding: 40px 20px;
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .page-header p {
            margin: 10px 0 0 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .about-card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .about-section {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
        }

        .about-image {
            flex: 1 1 300px;
        }

        .about-image img {
            width: 100%;
            border-radius: 15px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .about-text {
            flex: 2 1 400px;
        }

        .about-text h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #d6336c;
        }

        .about-text p {
            font-size: 1.05rem;
            color: #555;
            line-height: 1.8;
        }

        .features {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 30px;
            border-radius: 15px;
        }

        .features h3 {
            margin-top: 0;
            color: #d6336c;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .features ul {
            list-style: none;
            padding-left: 0;
        }

        .features ul li {
            margin-bottom: 15px;
            padding-left: 30px;
            position: relative;
            font-size: 1.05rem;
        }

        .features ul li::before {
            content: "\f00c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #d6336c;
            position: absolute;
            left: 0;
        }

        footer {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: white;
            margin-top: 60px;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            .about-section {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="<?= $isLoggedIn ? ($role === 'client' ? 'dashboard_client.php' : 'dashboard_admin.php') : 'login.php' ?>">
        <i class="fas fa-ring"></i> Mae's Bridal Shop
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background-color: white;">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($isLoggedIn && $role === 'client'): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard_client.php#hero">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="booking_history.php">Booking History</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
        <?php elseif ($isLoggedIn && $role === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if ($isLoggedIn): ?>
          <span class="text-white"><i class="fas fa-user"></i> <?= htmlspecialchars($fullname) ?></span>
          <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-sm btn-outline-light">Login</a>
          <a href="register.php" class="btn btn-sm btn-contact">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="fas fa-info-circle"></i> About Mae's Bridal Shop</h1>
    <p>Event Management Services and Reservation System</p>
</div>

<div class="container">

    <div class="about-card">
        <div class="about-section">
            <div class="about-image">
                <img src="images/logo.webp" alt="Mae's Bridal Shop">
            </div>
            <div class="about-text">
                <h2><i class="fas fa-heart"></i> About the System</h2>
                <p>
                    The <strong>Event Management Services and Reservation System</strong> of Mae's Bridal Shop is a
                    digital platform designed to streamline the process of managing bridal events, bookings, and service
                    reservations. Located in Sampaguita, Solana, Cagayan, the system allows customers to view services, make
                    appointments, and reserve packages easily through a user-friendly interface.
                </p>
                <p>
                    We specialize in making your special moments unforgettable with our comprehensive event services,
                    from intimate gatherings to grand celebrations.
                </p>
            </div>
        </div>
    </div>

    <div class="about-card">
        <div class="features">
            <h3><i class="fas fa-star"></i> Key Features of the System:</h3>
            <ul>
                <li>Online booking for bridal services and event packages</li>
                <li>Real-time reservation management for clients and admins</li>
                <li>Service catalog with descriptions and pricing</li>
                <li>Profile management for users</li>
                <li>Booking status tracking and notifications</li>
                <li>Admin panel for managing clients, appointments, and services</li>
                <li>Secure payment reference upload system</li>
                <li>Comprehensive booking history tracking</li>
            </ul>
        </div>
    </div>

</div>

<footer>
    <p><i class="fas fa-heart"></i> &copy; <?php echo date("Y"); ?> Mae's Bridal Shop | Sampaguita, Solana, Cagayan</p>
    <p>Making Your Special Day Memorable</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
