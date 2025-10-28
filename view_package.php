<?php
session_start();


$event = isset($_GET['event']) ? htmlspecialchars($_GET['event']) : 'Unknown Event';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Package - <?php echo $event; ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 30px;
      background-color: #f2f2f2;
    }

    .container {
      max-width: 800px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
    }

    .package {
      margin-bottom: 25px;
      padding: 20px;
      border-left: 5px solid #3498db;
      background-color: #fafafa;
      border-radius: 8px;
      cursor: pointer;
    }

    .package h2 {
      margin: 0 0 10px;
      color: #2c3e50;
    }

    .package ul {
      margin: 0;
      padding-left: 20px;
    }

    .btn-back {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #3498db;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }

    .btn-back:hover {
      background-color: #2980b9;
    }

    .package img {
      margin-top: 15px;
      max-width: 100%;
      border-radius: 8px;
      display: none; /* hidden by default */
    }
  </style>
</head>
<body>

<div class="container">
  <h1><?php echo strtoupper($event); ?> PACKAGES</h1>

  <div class="package" onclick="toggleImage('basic-img')">
    <h2>BASIC PACKAGE</h2>
    <ul>
      <li>Venue decoration / Gowns / Themes</li>
      <li><strong>Price:</strong> ₱10,000</li>
    </ul>
    <img id="basic-img" src="images/basic_package.jpg" alt="Basic Package">
  </div>

  <div class="package" onclick="toggleImage('silver-img')">
    <h2>SILVER PACKAGE</h2>
    <ul>
      <li>Customized theme decoration / Gowns / Themes</li>
      <li><strong>Price:</strong> ₱20,000</li>
    </ul>
    <img id="silver-img" src="images/silver_package.jpg" alt="Silver Package">
  </div>

  <div class="package" onclick="toggleImage('gold-img')">
    <h2>GOLD PACKAGE</h2>
    <ul>
      <li>Full event planning including all services</li>
      <li><strong>Price:</strong> ₱30,000</li>
    </ul>
    <img id="gold-img" src="images/gold_package.jpg" alt="Gold Package">
  </div>

  <a href="dashboard_client.php#services" class="btn-back">← Back to Services</a>
</div>

<script>
  function toggleImage(id) {
    const img = document.getElementById(id);
    img.style.display = (img.style.display === "none" || img.style.display === "") ? "block" : "none";
  }
</script>

</body>
</html>
