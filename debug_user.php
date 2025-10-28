<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    die("Please login first");
}

// Get current user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>User Info Debug</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
            background: #f5f5f5;
        }
        .info-box {
            background: white;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-box h3 {
            margin-top: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table td:first-child {
            font-weight: bold;
            width: 200px;
            color: #555;
        }
        .null-value {
            color: #999;
            font-style: italic;
        }
        .empty-value {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>🔍 User Information Debug</h1>
    
    <div class="info-box">
        <h3>Current Session Data</h3>
        <table>
            <tr>
                <td>Username:</td>
                <td><?= htmlspecialchars($_SESSION['username'] ?? 'NOT SET') ?></td>
            </tr>
            <tr>
                <td>Role:</td>
                <td><?= htmlspecialchars($_SESSION['role'] ?? 'NOT SET') ?></td>
            </tr>
            <tr>
                <td>User ID:</td>
                <td><?= htmlspecialchars($_SESSION['user_id'] ?? 'NOT SET') ?></td>
            </tr>
        </table>
    </div>
    
    <div class="info-box">
        <h3>Database User Record</h3>
        <?php if ($currentUser): ?>
        <table>
            <?php foreach ($currentUser as $key => $value): ?>
            <tr>
                <td><?= htmlspecialchars($key) ?>:</td>
                <td>
                    <?php 
                    if ($value === null) {
                        echo '<span class="null-value">NULL</span>';
                    } elseif ($value === '') {
                        echo '<span class="empty-value">EMPTY STRING</span>';
                    } elseif ($key === 'password') {
                        echo '<span style="color: #999;">***hidden***</span>';
                    } else {
                        echo htmlspecialchars($value);
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p style="color: #e74c3c;">User not found in database!</p>
        <?php endif; ?>
    </div>
    
    <?php if ($currentUser && ($currentUser['firstname'] === '' || $currentUser['lastname'] === '')): ?>
    <div class="info-box" style="background: #fff3cd; border-left: 4px solid #ffc107;">
        <h3>⚠️ Warning: Empty Name Fields</h3>
        <p>The firstname and/or lastname fields are empty for this user.</p>
        <p>This may cause issues in the chat system where names are displayed.</p>
        <p><strong>Recommendation:</strong> Update these fields with proper values.</p>
        
        <form method="POST" action="quick_fix_user.php" style="margin-top: 15px;">
            <label>First Name: <input type="text" name="firstname" value="<?= htmlspecialchars($currentUser['firstname']) ?>" required></label><br><br>
            <label>Last Name: <input type="text" name="lastname" value="<?= htmlspecialchars($currentUser['lastname']) ?>" required></label><br><br>
            <button type="submit" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Update My Name
            </button>
        </form>
    </div>
    <?php endif; ?>
    
    <div class="info-box">
        <h3>🔗 Quick Links</h3>
        <a href="test_chat.php" style="display: inline-block; margin: 5px; padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">Test Chat Page</a>
        <a href="<?= $currentUser['role'] === 'admin' ? 'messages.php' : 'contact.php' ?>" style="display: inline-block; margin: 5px; padding: 10px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 4px;">
            <?= $currentUser['role'] === 'admin' ? 'Admin Messages' : 'Contact/Chat' ?>
        </a>
        <a href="<?= $currentUser['role'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_client.php' ?>" style="display: inline-block; margin: 5px; padding: 10px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Dashboard</a>
    </div>
</body>
</html>
