<?php
session_start();
include 'config.php'; // Ensure $pdo is defined

if (isset($_SESSION["username"]) && isset($_SESSION["role"])) {
    $role = $_SESSION["role"];
   
        header("Location: dashboard_admin.php");
        exit;
    }


    
    // Other roles can remain on this page or be redirected as needed


$error = "";
$success = "";
$lockoutTime = 20; // Lockout duration in seconds

// Check for password reset success message
if (isset($_SESSION['password_reset_success'])) {
    $success = "Password reset successfully! You can now login with your new password.";
    unset($_SESSION['password_reset_success']);
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = 0;
}

$remainingTime = 0;

// Lockout logic
if ($_SESSION['login_attempts'] >= 3) {
    $elapsed = time() - $_SESSION['last_attempt_time'];
    if ($elapsed < $lockoutTime) {
        $remainingTime = $lockoutTime - $elapsed;
        $error = "Too many failed attempts. Try again after $remainingTime seconds.";
    } else {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt_time'] = 0;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $remainingTime == 0) {
    $username = $_POST["username"];
    $passwordInput = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($passwordInput, $user["password"])) {
        // Login successful
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt_time'] = 0;

        // Redirect based on role
        $redirectUrl = "dashboard_" . $user["role"] . ".php";
        echo "<script>
            alert('Login successful!');
            window.location.href = '$redirectUrl';
        </script>";
        exit();
    } else {
        $_SESSION['login_attempts'] += 1;
        $_SESSION['last_attempt_time'] = time();
        if ($_SESSION['login_attempts'] >= 3) {
            $error = "Too many failed attempts. Try again after $lockoutTime seconds.";
            $remainingTime = $lockoutTime;
        } else {
            $remaining = 3 - $_SESSION['login_attempts'];
            $error = "Invalid credentials. You have $remaining attempt(s) left.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mae’s Bridal Shop - Login</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('images/bg.jpeg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            width: 360px;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #d6336c;
            font-size: 20px;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        .login-box .show-password {
            font-size: 14px;
            margin-top: 5px;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background-color: #d6336c;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            margin-top: 15px;
            cursor: pointer;
            font-size: 16px;
        }

        .login-box button:disabled {
            background-color: #aaa;
            cursor: not-allowed;
        }

        .login-box a {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #007bff;
            font-size: 14px;
            text-decoration: none;
        }

        .error-msg {
            color: red;
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }

        .success-msg {
            color: #28a745;
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }

        .countdown {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            color: #d6336c;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Mae’s Bridal Shop</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" id="password" placeholder="Password" required>

        <div class="show-password">
            <input type="checkbox" onclick="togglePassword()"> Show Password
        </div>

        <button type="submit" id="loginBtn" <?= $remainingTime > 0 ? 'disabled' : '' ?>>Login</button>

        <div class="countdown" id="countdown" style="<?= $remainingTime > 0 ? '' : 'display:none;' ?>">
            Please wait <span id="timer"><?= $remainingTime ?></span> second(s)...
        </div>

        <a href="forgot_password.php">Forgot Password?</a>
        <a href="register.php">Create an Account</a>

        <?php if (!empty($success)): ?>
            <div class="success-msg"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </form>
</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}

<?php if ($remainingTime > 0): ?>
    let timeLeft = <?= $remainingTime ?>;
    const countdown = document.getElementById("countdown");
    const timerSpan = document.getElementById("timer");
    const loginBtn = document.getElementById("loginBtn");

    const interval = setInterval(() => {
        timeLeft--;
        timerSpan.textContent = timeLeft;
        if (timeLeft <= 0) {
            clearInterval(interval);
            countdown.style.display = "none";
            loginBtn.disabled = false;
        }
    }, 1000);
<?php endif; ?>
</script>

</body>
</html>
