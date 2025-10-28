<?php
session_start();
include 'config.php'; // Your PDO connection

$error = "";
$success = "";
$security_question = "";
$username = "";
$showResetForm = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get posted values safely
    $username = trim($_POST['username'] ?? '');
    $security_answer = trim($_POST['security_answer'] ?? '');
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (isset($_POST['check_username'])) {
        // Step 1: User submitted username to get security question
        if (empty($username)) {
            $error = "Please enter your username.";
        } else {
            // Fetch security question from DB
            $stmt = $pdo->prepare("SELECT security_question FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $security_question = $user['security_question'];
                $showResetForm = true; // Show the reset form now
                $error = "";
            } else {
                $error = "Username not found.";
            }
        }
    } elseif (isset($_POST['reset_password'])) {
        // Step 2: User submitted security answer and new passwords

        // Validate new password and confirm password
        if ($new_password !== $confirm_password) {
            $error = "New password and confirm password do not match.";
            $showResetForm = true; // Keep showing reset form
        } else {
            // Fetch user by username
            $stmt = $pdo->prepare("SELECT security_answer_hash, security_question FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $error = "Username not found.";
            } else {
                $security_question = $user['security_question'];
                // Check security answer (hashed with SHA256 in DB)
                if (hash('sha256', $security_answer) !== $user['security_answer_hash']) {
                    $error = "Security answer is incorrect.";
                    $showResetForm = true;
                } else {
                    // Update password with password_hash (bcrypt)
                    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

                    $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
                    $updateStmt->execute([$hashedPassword, $username]);

                    // Redirect to login page after successful password reset
                    $_SESSION['password_reset_success'] = true;
                    header('Location: login.php');
                    exit();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Forgot Password - Mae’s Bridal Shop</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(to right, #ffe4e1, #fff0f5);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .forgot-box {
        background: white;
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        width: 360px;
    }
    .forgot-box h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #d6336c;
        font-size: 24px;
    }
    .forgot-box input[type="text"],
    .forgot-box input[type="password"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0 20px 0;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 15px;
        box-sizing: border-box;
    }
    .forgot-box button {
        width: 100%;
        padding: 12px;
        background-color: #d6336c;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }
    .forgot-box button:hover {
        background-color: #b52b58;
    }
    .message {
        text-align: center;
        font-size: 14px;
        margin-top: 15px;
    }
    .error-msg {
        color: #d6336c;
    }
    .success-msg {
        color: #28a745;
    }
    a {
        color: #007bff;
        text-decoration: none;
        display: block;
        margin-top: 20px;
        text-align: center;
        font-size: 14px;
    }
    a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="forgot-box">
    <h2>Forgot Password</h2>

    <?php if (!$showResetForm): ?>
    <!-- Step 1: Ask for username -->
    <form method="post" novalidate>
        <input type="text" name="username" placeholder="Enter your username" value="<?= htmlspecialchars($username) ?>" required />
        <button type="submit" name="check_username">Get Security Question</button>
    </form>
    <?php else: ?>
    <!-- Step 2: Show security question and reset form -->
    <form method="post" novalidate>
        <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>" />
        <label><strong>Security Question:</strong></label>
        <p style="margin: 5px 0 15px 0; font-style: italic; color: #555;"><?= htmlspecialchars($security_question) ?></p>

        <input type="text" name="security_answer" placeholder="Your Security Answer" required />
        <input type="password" name="new_password" placeholder="New Password" required />
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required />
         <div class="show-password">
            <input type="checkbox" onclick="togglePassword()"> Show Password
        </div>

        <button type="submit" name="reset_password">Reset Password</button>
    </form>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error-msg"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="message success-msg"><?= $success ?></div>
    <?php endif; ?>

    <a href="login.php">Back to Login</a>
</div>

</body>
</html>
