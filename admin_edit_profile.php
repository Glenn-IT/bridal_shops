<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "bridal_event_system");

$username = $_SESSION['username'];

$query = $conn->query("SELECT * FROM users WHERE username='$username'");
$admin = $query->fetch_assoc();

$firstname = $admin['firstname'] ?? '';
$middlename = $admin['middlename'] ?? '';
$lastname = $admin['lastname'] ?? '';
$email = $admin['email'] ?? '';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_firstname = $conn->real_escape_string($_POST['firstname']);
    $new_middlename = $conn->real_escape_string($_POST['middlename']);
    $new_lastname = $conn->real_escape_string($_POST['lastname']);
    $new_email = $conn->real_escape_string($_POST['email']);
    $new_password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $admin['password'];

    $update = $conn->query("UPDATE users SET firstname='$new_firstname', middlename='$new_middlename', lastname='$new_lastname', email='$new_email', password='$new_password' WHERE username='$username'");

    if ($update) {
        $_SESSION['firstname'] = $new_firstname;
        $_SESSION['middlename'] = $new_middlename;
        $_SESSION['lastname'] = $new_lastname;
        $_SESSION['email'] = $new_email;
        $success = "Profile updated successfully!";
        $firstname = $new_firstname;
        $middlename = $new_middlename;
        $lastname = $new_lastname;
        $email = $new_email;
    } else {
        $error = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Admin Profile - Mae’s Bridal Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { margin: 0; padding: 0; background: #f1f2f6; }
        header { background: linear-gradient(to right, #6c5ce7, #a29bfe); color: white; padding: 1.5rem 2rem; text-align: center; font-size: 1.8rem; font-weight: 600; }
        .container { max-width: 600px; margin: 3rem auto; background: white; padding: 2rem; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #2d3436; margin-bottom: 2rem; }
        form label { display: block; margin-bottom: 0.4rem; font-weight: 500; color: #636e72; }
        form input { width: 100%; padding: 0.9rem; margin-bottom: 1.2rem; border: 1px solid #dfe6e9; border-radius: 8px; font-size: 1rem; }
        .btn { background: #6c5ce7; color: white; border: none; padding: 1rem 2rem; border-radius: 8px; font-size: 1.1rem; cursor: pointer; transition: background 0.2s; }
        .btn:hover { background: #a29bfe; }
        .message { text-align: center; margin-bottom: 1rem; font-weight: 500; }
        .success { color: #00b894; }
        .error { color: #d63031; }
    </style>
</head>
<body>
    <header>Edit Admin Profile</header>
    <div class="container">
        <h2>Edit Your Credentials</h2>
        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($firstname) ?>" required>
            <label for="middlename">Middle Name</label>
            <input type="text" id="middlename" name="middlename" value="<?= htmlspecialchars($middlename) ?>">
            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($lastname) ?>" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            <label for="password">New Password (leave blank to keep current)</label>
            <input type="password" id="password" name="password">
            <button type="submit" class="btn">Update Profile</button>
        </form>
    </div>
</body>
</html>
