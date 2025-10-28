<?php
session_start();

// Redirect if not logged in or not a client
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit();
}

// Session values
$fullname = $_SESSION['fullname'] ?? 'Unknown User';
$email = $_SESSION['email'] ?? 'noemail@example.com';
$contact = $_SESSION['contact'] ?? 'N/A';
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Mae’s Bridal Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f1f2f6;
        }

        header {
            background: linear-gradient(to right, #6c5ce7, #a29bfe);
            color: white;
            padding: 1.5rem 2rem;
            text-align: center;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .container {
            max-width: 700px;
            margin: 3rem auto;
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #6c5ce7;
            margin-bottom: 1rem;
        }

        .profile-header h2 {
            color: #2d3436;
            margin: 0;
            font-size: 1.7rem;
        }

        .profile-info {
            margin-top: 1.5rem;
        }

        .info-group {
            margin-bottom: 1.3rem;
        }

        .info-group label {
            font-weight: 600;
            color: #636e72;
            font-size: 0.95rem;
        }

        .info-group div {
            font-size: 1.15rem;
            color: #2d3436;
            margin-top: 0.3rem;
        }

        .actions {
            margin-top: 2.5rem;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }

        .actions a {
            flex: 1;
            padding: 0.9rem;
            background: #6c5ce7;
            color: white;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }

        .actions a.logout {
            background: #d63031;
        }

        .actions a:hover {
            opacity: 0.9;
        }

        @media (max-width: 500px) {
            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<header>
    Mae’s Bridal Shop - Client Profile
</header>

<div class="container">
    <div class="profile-header">
        <img src="https://via.placeholder.com/100x100.png?text=👤" alt="Profile Picture">
        <h2><?= htmlspecialchars($fullname) ?></h2>
    </div>

    <div class="profile-info">
        <div class="info-group">
            <label><i class="fas fa-user"></i> Username:</label>
            <div><?= htmlspecialchars($username) ?></div>
        </div>
        <div class="info-group">
            <label><i class="fas fa-envelope"></i> Email Address:</label>
            <div><?= htmlspecialchars($email) ?></div>
        </div>
        <div class="info-group">
            <label><i class="fas fa-phone"></i> Contact Number:</label>
            <div><?= htmlspecialchars($contact) ?></div>
        </div>
    </div>

    <div class="actions">
        <a href="edit_profile.php"><i class="fas fa-edit"></i> Edit Profile</a>
        <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

</body>
</html>
