<?php
session_start();

// Check if user is logged in as client
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
  <title>Our Event Services</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins&display=swap" rel="stylesheet">
  <!-- Bootstrap CSS for Navbar -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #2d2d2d;
      color: white;
      padding-top: 70px; /* Add padding for fixed navbar */
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

    header {
      text-align: center;
      padding: 60px 20px 20px;
    }

    .main-title {
      font-family: 'Playfair Display', serif;
      font-size: 2.8rem;
      margin-bottom: 10px;
    }

    .subtitle {
      font-size: 1.1rem;
      max-width: 700px;
      margin: auto;
      color: #ccc;
    }

    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 30px;
      padding: 40px;
      max-width: 1200px;
      margin: auto;
    }

    .card {
      background-color: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
      color: #333;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
    }

    .card-content {
      padding: 20px;
    }

    .card h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .card p {
      font-size: 0.95rem;
      color: #555;
      margin-bottom: 10px;
    }

    .btn {
      display: inline-block;
      padding: 8px 16px;
      background-color: #e76f51;
      color: white;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 500;
      font-size: 0.9rem;
      margin-right: 10px;
      transition: background 0.2s ease;
    }

    .btn:hover {
      background-color: #d45b3f;
    }

    @media (max-width: 480px) {
      .main-title {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
  <div class="container">
    <a class="navbar-brand" href="<?= $isLoggedIn ? ($role === 'client' ? 'dashboard_client.php' : 'dashboard_admin.php') : 'login.php' ?>">Mae's Bridal Shop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background-color: white;">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($isLoggedIn && $role === 'client'): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard_client.php#hero">Home</a></li>
          <li class="nav-item"><a class="nav-link active" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard_client.php#booknow">Book Now</a></li>
          <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="booking_history.php">Booking History</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <?php elseif ($isLoggedIn && $role === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link active" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="manage_events.php">Manage Events</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link active" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if ($isLoggedIn): ?>
          <span class="text-white"><?= htmlspecialchars($fullname) ?></span>
          <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
          <?php if ($role === 'client'): ?>
            <a href="contact.php" class="btn btn-sm btn-contact">Contact Us</a>
          <?php endif; ?>
        <?php else: ?>
          <a href="login.php" class="btn btn-sm btn-outline-light">Login</a>
          <a href="register.php" class="btn btn-sm btn-contact">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<header>
  <h1 class="main-title">Preserving Memories For All Occasions</h1>
  <p class="subtitle">From tearjerking family reunions to lavish milestones, Mae’s Bridal Shop captures every story worth remembering.</p>
</header>

<section class="services-grid">
  <?php
  $services = [
    [
      'title' => 'Birthday Collections',
      'desc' => 'From intimate gatherings to extravagant parties, celebrate another year with timeless moments.',
      'images' => 'bg.jpeg',
      'type' => 'Birthday'
    ],
    [
      'title' => 'Wedding Collections',
      'desc' => 'Celebrate your “I do” with elegance. Our lens captures every kiss, every glance, every vow.',
      'images' => 'se.jpeg',
      'type' => 'Wedding'
    ],
    [
      'title' => 'Anniversary Collections',
      'desc' => 'Whether it’s the 1st or 50th, let us preserve your years of love and laughter.',
      'images' => 'anv.webp',
      'type' => 'Anniversary'
    ],
    [
      'title' => 'Corporate Events',
      'desc' => 'Professional events deserve professional coverage. We document seminars, parties, launches, and more.',
      'images' => 'cor.jpg',
      'type' => 'Corporate'
    ]
  ];

  foreach ($services as $service): ?>
    <div class="card">
      <img src="images/<?= htmlspecialchars($service['images']) ?>" alt="<?= htmlspecialchars($service['title']) ?>">
      <div class="card-content">
        <h3><?= htmlspecialchars($service['title']) ?></h3>
        <p><?= htmlspecialchars($service['desc']) ?></p>
        <a href="view_package.php?event=<?= urlencode($service['type']) ?>" class="btn">View Package</a>
        <a href="book_now.php?events=<?= urlencode($service['type']) ?>" class="btn">Book Now</a>
      </div>
    </div>
  <?php endforeach; ?>
</section>

<!-- Bootstrap JS for Navbar Toggle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
