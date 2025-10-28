<?php
session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Event Services</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #2d2d2d;
      color: white;
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

</body>
</html>
