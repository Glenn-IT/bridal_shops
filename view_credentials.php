<?php
// SECURITY WARNING: This file should be DELETED in production environment
// This file bypasses all security - for development use only

// Database configuration - adjust these to match your config
$host = 'localhost';
$db   = 'bridal_event_system';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// First, let's check what columns exist in the users table
try {
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<!-- Debug: Available columns: " . implode(', ', $columns) . " -->";
} catch (Exception $e) {
    die("Error checking table structure: " . $e->getMessage());
}

// Fetch all users directly from database - only existing columns
$stmt = $pdo->query("SELECT id, username, email, password, role FROM users ORDER BY id");
$users = $stmt->fetchAll();

// Common passwords to test against (extensive list)
$common_passwords = [
    // Basic common passwords
    'admin123', 'password', '123456', 'admin', 'test123', 'password123',
    '12345678', 'qwerty', 'abc123', 'letmein', 'monkey', 'shadow',
    'master', 'dragon', 'passw0rd', '1234567', '123456789', '12345',
    '1234567890', '111111', 'sunshine', 'iloveyou', 'starwars', 'computer',
    'whatever', 'hello', 'freedom', 'charlie', 'aa123456', 'password1',
    
    // Bridal shop related
    'bridal', 'wedding', 'bride', 'groom', 'mae', 'maesbridal',
    'event', 'bridalshop', 'admin@123', 'shop123', 'bridal123',
    
    // Simple patterns
    'test', 'demo', 'user', 'guest', 'root', 'super', 'manager',
    'staff', 'employee', 'client', 'customer'
];

// Test each user's password
$credentials = [];
foreach ($users as $user) {
    $unhashed_password = '*** HASHED - UNABLE TO RECOVER ***';
    
    // Try to find the unhashed password
    foreach ($common_passwords as $test_pwd) {
        if (password_verify($test_pwd, $user['password'])) {
            $unhashed_password = $test_pwd;
            break;
        }
    }
    
    $credentials[] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'role' => $user['role'],
        'hashed_password' => $user['password'],
        'unhashed_password' => $unhashed_password
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DATABASE CREDENTIALS VIEWER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .security-warning {
            background: #dc3545;
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: 3px solid #ffc107;
        }
        .credentials-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .table-header {
            background: #2c3e50;
            color: white;
        }
        .password-found {
            background-color: #d4edda !important;
            font-weight: bold;
        }
        .password-not-found {
            background-color: #f8d7da !important;
        }
        .hashed-password {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Security Warning -->
        <div class="security-warning text-center">
            <h1>🚨 SECURITY ALERT 🚨</h1>
            <h3>DEVELOPMENT MODE - CREDENTIALS VIEWER</h3>
            <p class="mb-0">
                <strong>This page exposes all user credentials!</strong><br>
                DELETE THIS FILE BEFORE DEPLOYING TO PRODUCTION SERVER
            </p>
        </div>

        <!-- Database Info -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Database Information</h5>
                        <p class="card-text mb-1">Database: <?= htmlspecialchars($db) ?></p>
                        <p class="card-text mb-1">Host: <?= htmlspecialchars($host) ?></p>
                        <p class="card-text mb-0">Total Users: <?= count($credentials) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Recovery Status</h5>
                        <?php
                        $recovered = count(array_filter($credentials, function($cred) {
                            return $cred['unhashed_password'] !== '*** HASHED - UNABLE TO RECOVER ***';
                        }));
                        ?>
                        <p class="card-text mb-1">Passwords Recovered: <?= $recovered ?> / <?= count($credentials) ?></p>
                        <p class="card-text mb-0">Success Rate: <?= round(($recovered / count($credentials)) * 100, 2) ?>%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Credentials Table -->
        <div class="credentials-table">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-header">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Hashed Password</th>
                        <th>Unhashed Password</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($credentials as $cred): ?>
                    <?php 
                    $isRecovered = $cred['unhashed_password'] !== '*** HASHED - UNABLE TO RECOVER ***';
                    $rowClass = $isRecovered ? 'password-found' : 'password-not-found';
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td><strong><?= htmlspecialchars($cred['id']) ?></strong></td>
                        <td><strong><?= htmlspecialchars($cred['username']) ?></strong></td>
                        <td><?= htmlspecialchars($cred['email']) ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $cred['role'] === 'admin' ? 'danger' : 
                                ($cred['role'] === 'staff' ? 'warning' : 'info')
                            ?>">
                                <?= htmlspecialchars($cred['role']) ?>
                            </span>
                        </td>
                        <td>
                            <small class="hashed-password text-muted">
                                <?= htmlspecialchars($cred['hashed_password']) ?>
                            </small>
                        </td>
                        <td>
                            <?php if ($isRecovered): ?>
                                <code class="bg-success text-white p-2 rounded d-inline-block">
                                    <strong><?= htmlspecialchars($cred['unhashed_password']) ?></strong>
                                </code>
                            <?php else: ?>
                                <span class="text-danger fw-bold">UNABLE TO RECOVER</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($isRecovered): ?>
                                <span class="badge bg-success">RECOVERED</span>
                            <?php else: ?>
                                <span class="badge bg-danger">ENCRYPTED</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Common Passwords Tested -->
        <div class="mt-4 p-4 bg-light rounded">
            <h5>Common Passwords Tested (<?= count($common_passwords) ?> total):</h5>
            <div class="d-flex flex-wrap gap-1">
                <?php foreach ($common_passwords as $pwd): ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($pwd) ?></span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-4 text-center">
            <div class="btn-group">
                <a href="login.php" class="btn btn-primary">Go to Login Page</a>
                <button onclick="location.reload()" class="btn btn-secondary">Refresh Data</button>
                <button onclick="window.print()" class="btn btn-info">Print This Page</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>