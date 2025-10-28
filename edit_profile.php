<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "bridal_event_system");

$username = $_SESSION['username'];

$query = $conn->query("SELECT * FROM users WHERE username='$username'");
$user = $query->fetch_assoc();

$fullname = $user['fullname'] ?? '';
$email = $user['email'] ?? '';
$contact = $user['contact'] ?? '';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_fullname = $conn->real_escape_string($_POST['fullname']);
    $new_email = $conn->real_escape_string($_POST['email']);
    $new_contact = $conn->real_escape_string($_POST['contact']);

    $update = $conn->query("UPDATE users SET fullname='$new_fullname', email='$new_email', contact='$new_contact' WHERE username='$username'");

    if ($update) {
        $_SESSION['fullname'] = $new_fullname;
        $_SESSION['email'] = $new_email;
        $_SESSION['contact'] = $new_contact;
        $success = "Profile updated successfully!";
        // Refresh values
        $fullname = $new_fullname;
        $email = $new_email;
        $contact = $new_contact;
    } else {
        $error = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Mae’s Bridal Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts & Icons -->
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
        }

        .container {
            max-width: 600px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2d3436;
            margin-bottom: 2rem;
        }

        form label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 500;
            color: #636e72;
        }

        form input {
            width: 100%;
            padding: 0.9rem;
            margin-bottom: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            transition: 0.3s;
        }

        form input:focus {
            border-color: #6c5ce7;
            outline: none;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }

        .btn-group button,
        .btn-group a {
            flex: 1;
            padding: 0.9rem;
            text-align: center;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-save {
            background: #6c5ce7;
            color: white;
        }

        .btn-save:hover {
            background: #a29bfe;
        }

        .btn-cancel {
            background: #d63031;
            color: white;
        }

        .btn-cancel:hover {
            background: #e17055;
        }

        .message {
            text-align: center;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        @media (max-width: 500px) {
            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<header>
    Mae’s Bridal Shop - Edit Profile
</header>

<div class="container">
    <h2><i class="fas fa-user-edit"></i> Edit Your Information</h2>

    <?php if ($success): ?>
        <div class="message success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="fullname">Full Name:</label>
        <input type="text" name="fullname" id="fullname" value="<?= htmlspecialchars($fullname) ?>" required>

        <label for="email">Email Address:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="contact">Contact Number:</label>
        <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($contact) ?>" required>

        <div class="btn-group">
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Save Changes</button>
            <a href="profile.php" class="btn-cancel"><i class="fas fa-times-circle"></i> Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
