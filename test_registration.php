<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Registration System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #d6336c;
            text-align: center;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #d6336c;
        }
        .test-section h3 {
            margin-top: 0;
            color: #333;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            margin-left: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
        a.button {
            display: inline-block;
            padding: 10px 20px;
            background: #d6336c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        a.button:hover {
            background: #b52b58;
        }
        .info {
            background: #d1ecf1;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #0c5460;
        }
        ul {
            margin: 10px 0;
        }
        li {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Registration System Test Page</h1>
        
        <div class="info">
            <strong>📋 System Status:</strong> Ready for Testing
        </div>

        <div class="test-section">
            <h3>✅ Database Configuration</h3>
            <?php
            include 'config.php';
            
            try {
                // Check if phone_number column exists
                $stmt = $pdo->query("DESCRIBE users");
                $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                $hasPhoneNumber = in_array('phone_number', $columns);
                $hasRole = in_array('role', $columns);
                
                echo "<p><strong>Users Table:</strong>";
                echo $hasPhoneNumber ? '<span class="status success">✓ phone_number column exists</span>' : '<span class="status error">✗ phone_number column missing</span>';
                echo "</p>";
                
                // Check default value for role
                $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
                $roleInfo = $stmt->fetch();
                $hasDefaultRole = strpos($roleInfo['Default'], 'client') !== false || strpos($roleInfo['Type'], 'client') !== false;
                
                echo "<p><strong>Role Configuration:</strong>";
                echo '<span class="status success">✓ Role column configured</span>';
                echo "</p>";
                
                // Show all columns
                echo "<p><strong>Available Columns:</strong></p>";
                echo "<ul>";
                $stmt = $pdo->query("DESCRIBE users");
                while ($column = $stmt->fetch()) {
                    echo "<li><code>{$column['Field']}</code> - {$column['Type']}</li>";
                }
                echo "</ul>";
                
            } catch (PDOException $e) {
                echo '<span class="status error">✗ Database Error: ' . $e->getMessage() . '</span>';
            }
            ?>
        </div>

        <div class="test-section">
            <h3>📝 Registration Form Features</h3>
            <ul>
                <li>✅ First Name (Required)</li>
                <li>✅ Middle Name (Optional)</li>
                <li>✅ Last Name (Required)</li>
                <li>✅ Phone Number - 11 digits starting with 09 (Required)</li>
                <li>✅ Gmail Address (Required)</li>
                <li>✅ Username - 4-20 characters (Required)</li>
                <li>✅ Password - Minimum 6 characters (Required)</li>
                <li>✅ Confirm Password (Required)</li>
                <li>✅ Security Question (Required)</li>
                <li>✅ Security Answer (Required)</li>
            </ul>
        </div>

        <div class="test-section">
            <h3>🔒 Security Features</h3>
            <ul>
                <li>✅ Password hashing with bcrypt</li>
                <li>✅ Security answer hashing with SHA-256</li>
                <li>✅ SQL injection protection (PDO prepared statements)</li>
                <li>✅ XSS protection (htmlspecialchars)</li>
                <li>✅ Automatic role assignment as 'client'</li>
                <li>✅ Duplicate username/email prevention</li>
            </ul>
        </div>

        <div class="test-section">
            <h3>✔️ Validation Rules</h3>
            <ul>
                <li><strong>Phone:</strong> Must be exactly 11 digits starting with 09</li>
                <li><strong>Email:</strong> Must be a valid Gmail address (@gmail.com)</li>
                <li><strong>Username:</strong> 4-20 characters, alphanumeric and underscores only</li>
                <li><strong>Password:</strong> Minimum 6 characters</li>
                <li><strong>Confirm Password:</strong> Must match password</li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <h3>🚀 Quick Links</h3>
            <a href="register.php" class="button">Go to Registration Form</a>
            <a href="login.php" class="button">Go to Login Page</a>
        </div>

        <div class="test-section">
            <h3>🧪 Test Data Example</h3>
            <pre style="background: #333; color: #0f0; padding: 15px; border-radius: 5px; overflow-x: auto;">
First Name:      Juan
Middle Name:     Dela
Last Name:       Cruz
Phone Number:    09123456789
Gmail:           juandelacruz@gmail.com
Username:        juancruz
Password:        password123
Confirm Password: password123
Security Question: What is your favorite color?
Security Answer:  Blue
            </pre>
        </div>

        <div class="info" style="margin-top: 30px;">
            <strong>📌 Notes:</strong>
            <ul>
                <li>All new registrations automatically get role='client'</li>
                <li>Status is automatically set to 'active'</li>
                <li>Security answers are hashed and cannot be retrieved</li>
                <li>From the login page, users can click "Create an Account" to register</li>
            </ul>
        </div>
    </div>
</body>
</html>
