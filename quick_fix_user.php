<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');

if (empty($firstname) || empty($lastname)) {
    die("First name and last name are required");
}

try {
    $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ? WHERE username = ?");
    $stmt->execute([$firstname, $lastname, $_SESSION['username']]);
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Update Successful</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 20px;
                text-align: center;
                background: #f5f5f5;
            }
            .success-box {
                background: #d4edda;
                border: 1px solid #c3e6cb;
                color: #155724;
                padding: 30px;
                border-radius: 8px;
                max-width: 500px;
                margin: 50px auto;
            }
            .success-box h2 {
                margin-top: 0;
            }
            .button {
                display: inline-block;
                padding: 10px 20px;
                margin: 10px;
                background: #28a745;
                color: white;
                text-decoration: none;
                border-radius: 4px;
            }
        </style>
    </head>
    <body>
        <div class='success-box'>
            <h2>✅ Update Successful!</h2>
            <p>Your name has been updated to: <strong>$firstname $lastname</strong></p>
            <p>You can now use the chat system with your proper name displayed.</p>
            <a href='debug_user.php' class='button'>View User Info</a>
            <a href='test_chat.php' class='button'>Test Chat</a>
        </div>
    </body>
    </html>";
    
} catch (PDOException $e) {
    die("Error updating user: " . htmlspecialchars($e->getMessage()));
}
?>
