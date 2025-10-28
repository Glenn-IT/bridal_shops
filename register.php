<?php
session_start();
include 'config.php';

$error = "";
$success = "";

// Predefined security questions
$security_questions = [
    "What is your mother's maiden name?",
    "What is your favorite color?",
    "What is the name of your first pet?",
    "What city were you born in?",
    "What is your favorite food?"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input
    $firstname = trim($_POST['firstname'] ?? '');
    $middlename = trim($_POST['middlename'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $security_question = $_POST['security_question'] ?? '';
    $security_answer = trim($_POST['security_answer'] ?? '');

    // Validation
    $errors = [];

    // Required fields validation
    if (empty($firstname)) $errors[] = "First name is required.";
    if (empty($lastname)) $errors[] = "Last name is required.";
    if (empty($phone_number)) $errors[] = "Phone number is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (empty($username)) $errors[] = "Username is required.";
    if (empty($password)) $errors[] = "Password is required.";
    if (empty($confirm_password)) $errors[] = "Confirm password is required.";
    if (empty($security_question)) $errors[] = "Security question is required.";
    if (empty($security_answer)) $errors[] = "Security answer is required.";

    // Phone number validation (must be 11 digits starting with 09)
    if (!empty($phone_number)) {
        if (!preg_match('/^09\d{9}$/', $phone_number)) {
            $errors[] = "Phone number must be 11 digits and start with 09.";
        }
    }

    // Email validation
    if (!empty($email)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
        // Check if email contains gmail
        if (!strpos(strtolower($email), 'gmail.com')) {
            $errors[] = "Please use a Gmail address.";
        }
    }

    // Username validation (alphanumeric, 4-20 characters)
    if (!empty($username)) {
        if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
            $errors[] = "Username must be 4-20 characters and contain only letters, numbers, and underscores.";
        }
    }

    // Password validation
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long.";
        }
    }

    // Confirm password match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if username already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $errors[] = "Username already exists. Please choose another.";
        }
    }

    // Check if email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Email already registered. Please use another email.";
        }
    }

    // Check if the combination of firstname, middlename, and lastname already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE firstname = ? AND middlename = ? AND lastname = ?");
        $stmt->execute([$firstname, $middlename, $lastname]);
        if ($stmt->fetch()) {
            $errors[] = "A user with this exact name already exists. Please verify your information.";
        }
    }

    // If no errors, insert into database
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $security_answer_hash = hash('sha256', $security_answer);

            $stmt = $pdo->prepare("INSERT INTO users (firstname, middlename, lastname, phone_number, username, password, role, email, security_question, security_answer_hash, status) VALUES (?, ?, ?, ?, ?, ?, 'client', ?, ?, ?, 'active')");
            
            $stmt->execute([
                $firstname,
                $middlename,
                $lastname,
                $phone_number,
                $username,
                $hashed_password,
                $email,
                $security_question,
                $security_answer_hash
            ]);

            $success = "Registration successful! You can now <a href='login.php' style='color: #d6336c; font-weight: bold;'>login</a>.";
            
            // Clear form data on success
            $firstname = $middlename = $lastname = $phone_number = $email = $username = $security_answer = '';
        } catch (PDOException $e) {
            $errors[] = "Registration failed: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration - Mae's Bridal Shop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('images/bg.jpeg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.96);
            padding: 40px 35px;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #d6336c;
            font-size: 28px;
        }

        .register-container .subtitle {
            text-align: center;
            margin-bottom: 25px;
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group label .required {
            color: #d6336c;
            margin-left: 2px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #d6336c;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .show-password {
            display: flex;
            align-items: center;
            margin-top: 8px;
            font-size: 14px;
        }

        .show-password input[type="checkbox"] {
            margin-right: 6px;
            cursor: pointer;
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background-color: #d6336c;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn-register:hover {
            background-color: #b52b58;
        }

        .message {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .error-msg {
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .success-msg {
            background-color: #efe;
            color: #2a7;
            border: 1px solid #cfc;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .info-text {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .register-container {
                padding: 30px 25px;
            }
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Customer Registration</h2>
    <p class="subtitle">Mae's Bridal Shop</p>

    <?php if (!empty($error)): ?>
        <div class="message error-msg"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="message success-msg"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" action="" id="registerForm">
        <!-- Name Fields -->
        <div class="form-row">
            <div class="form-group">
                <label>First Name <span class="required">*</span></label>
                <input type="text" name="firstname" value="<?= htmlspecialchars($firstname ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Middle Name</label>
                <input type="text" name="middlename" value="<?= htmlspecialchars($middlename ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Last Name <span class="required">*</span></label>
            <input type="text" name="lastname" value="<?= htmlspecialchars($lastname ?? '') ?>" required>
        </div>

        <!-- Contact Information -->
        <div class="form-group">
            <label>Phone Number <span class="required">*</span></label>
            <input type="text" name="phone_number" id="phone_number" value="<?= htmlspecialchars($phone_number ?? '') ?>" placeholder="09XXXXXXXXX" maxlength="11" required>
            <div class="info-text">Must be 11 digits starting with 09</div>
        </div>

        <div class="form-group">
            <label>Gmail <span class="required">*</span></label>
            <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" placeholder="example@gmail.com" required>
            <div class="info-text">Please use a Gmail address</div>
        </div>

        <!-- Account Information -->
        <div class="form-group">
            <label>Username <span class="required">*</span></label>
            <input type="text" name="username" value="<?= htmlspecialchars($username ?? '') ?>" placeholder="Choose a username" required>
            <div class="info-text">4-20 characters, letters, numbers and underscores only</div>
        </div>

        <div class="form-group">
            <label>Password <span class="required">*</span></label>
            <input type="password" name="password" id="password" placeholder="Enter password" required>
            <div class="info-text">Minimum 6 characters</div>
        </div>

        <div class="form-group">
            <label>Confirm Password <span class="required">*</span></label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" required>
        </div>

        <div class="show-password">
            <input type="checkbox" id="showPasswordCheckbox" onclick="togglePassword()">
            <label for="showPasswordCheckbox" style="margin: 0; font-weight: normal;">Show Password</label>
        </div>

        <!-- Security Question -->
        <div class="form-group" style="margin-top: 20px;">
            <label>Security Question <span class="required">*</span></label>
            <select name="security_question" required>
                <option value="">-- Select a security question --</option>
                <?php foreach ($security_questions as $question): ?>
                    <option value="<?= htmlspecialchars($question) ?>" <?= (isset($security_question) && $security_question === $question) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($question) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Security Answer <span class="required">*</span></label>
            <input type="text" name="security_answer" value="<?= htmlspecialchars($security_answer ?? '') ?>" placeholder="Your answer" required>
            <div class="info-text">This will be used for password recovery</div>
        </div>

        <button type="submit" class="btn-register">Register</button>
    </form>

    <div class="login-link">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

<script>
    function togglePassword() {
        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirm_password");
        const checkbox = document.getElementById("showPasswordCheckbox");
        
        if (checkbox.checked) {
            password.type = "text";
            confirmPassword.type = "text";
        } else {
            password.type = "password";
            confirmPassword.type = "password";
        }
    }

    // Phone number validation - allow only numbers
    document.getElementById('phone_number').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Form validation before submit
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const phone = document.getElementById('phone_number').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        // Phone validation
        if (!/^09\d{9}$/.test(phone)) {
            alert('Phone number must be 11 digits and start with 09');
            e.preventDefault();
            return false;
        }

        // Password match validation
        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            e.preventDefault();
            return false;
        }

        // Password length validation
        if (password.length < 6) {
            alert('Password must be at least 6 characters long!');
            e.preventDefault();
            return false;
        }
    });
</script>

</body>
</html>
