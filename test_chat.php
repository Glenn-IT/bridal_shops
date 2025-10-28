<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    die("Please <a href='login.php'>login</a> first to test chat");
}

// Get user_id if not set
if (!isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
    }
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];
$userId = $_SESSION['user_id'] ?? 'NOT SET';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat System Test</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .section {
            margin: 20px 0;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        .section h2 {
            color: #555;
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        .info-item {
            padding: 10px;
            margin: 10px 0;
            background: #f9f9f9;
            border-left: 4px solid #4CAF50;
        }
        .info-item strong {
            color: #333;
        }
        .test-btn {
            background: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        .test-btn:hover {
            background: #45a049;
        }
        .test-btn.danger {
            background: #f44336;
        }
        .test-btn.danger:hover {
            background: #da190b;
        }
        .result {
            margin: 15px 0;
            padding: 15px;
            border-radius: 5px;
            display: none;
        }
        .result.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            display: block;
        }
        .result.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            display: block;
        }
        .result.info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            display: block;
        }
        pre {
            background: #272822;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            margin: 10px 0;
        }
        .chat-test {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        .chat-box {
            border: 2px solid #ddd;
            height: 300px;
            overflow-y: auto;
            padding: 15px;
            background: #fafafa;
            border-radius: 5px;
        }
        .chat-input-area {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .chat-input-area input {
            flex: 1;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            background: white;
            border-left: 3px solid #4CAF50;
        }
        .message.sent {
            background: #e3f2fd;
            border-left-color: #2196F3;
        }
        .message-time {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        @media (max-width: 768px) {
            .chat-test {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Chat System Test Page</h1>
        
        <div class="section">
            <h2>📋 Session Information</h2>
            <div class="info-item"><strong>Username:</strong> <?= htmlspecialchars($username) ?></div>
            <div class="info-item"><strong>Role:</strong> <?= htmlspecialchars($role) ?></div>
            <div class="info-item"><strong>User ID:</strong> <?= htmlspecialchars($userId) ?></div>
        </div>

        <div class="section">
            <h2>🗄️ Database Connection Test</h2>
            <button class="test-btn" onclick="testDatabase()">Test Database</button>
            <div id="dbResult" class="result"></div>
        </div>

        <div class="section">
            <h2>📊 Chat Tables Test</h2>
            <button class="test-btn" onclick="testTables()">Check Tables</button>
            <div id="tablesResult" class="result"></div>
        </div>

        <div class="section">
            <h2>🔌 API Endpoints Test</h2>
            <button class="test-btn" onclick="testConversation()">Test Get/Create Conversation</button>
            <button class="test-btn" onclick="testSendMessage()">Test Send Message</button>
            <button class="test-btn" onclick="testGetMessages()">Test Get Messages</button>
            <?php if ($role === 'admin'): ?>
            <button class="test-btn" onclick="testGetConversations()">Test Get Conversations</button>
            <?php endif; ?>
            <div id="apiResult" class="result"></div>
        </div>

        <div class="section">
            <h2>💬 Live Chat Test</h2>
            <p><strong>Conversation ID:</strong> <span id="convId">Not initialized</span></p>
            
            <div class="chat-test">
                <div>
                    <h3>Chat Messages</h3>
                    <div class="chat-box" id="testChatBox">
                        <p style="text-align: center; color: #999;">No messages yet</p>
                    </div>
                    <div class="chat-input-area">
                        <input type="text" id="testMessageInput" placeholder="Type a test message..." onkeypress="if(event.key==='Enter') sendTestMessage()">
                        <button class="test-btn" onclick="sendTestMessage()">Send</button>
                    </div>
                </div>
                
                <div>
                    <h3>Debug Log</h3>
                    <div class="chat-box" id="debugLog">
                        <p style="color: #999;">Debug information will appear here...</p>
                    </div>
                    <button class="test-btn" onclick="clearDebug()">Clear Log</button>
                    <button class="test-btn" onclick="refreshMessages()">Refresh Messages</button>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🧹 Cleanup</h2>
            <button class="test-btn danger" onclick="clearTestData()">Clear Test Conversations</button>
            <div id="cleanupResult" class="result"></div>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="<?= $role === 'admin' ? 'messages.php' : 'contact.php' ?>" class="test-btn">Go to Real Chat</a>
            <a href="<?= $role === 'admin' ? 'dashboard_admin.php' : 'dashboard_client.php' ?>" class="test-btn">Go to Dashboard</a>
        </div>
    </div>

    <script>
        let testConversationId = null;
        let lastTestMessageId = 0;

        function log(message, type = 'info') {
            const debugLog = document.getElementById('debugLog');
            const time = new Date().toLocaleTimeString();
            const color = type === 'error' ? '#f44336' : type === 'success' ? '#4CAF50' : '#2196F3';
            debugLog.innerHTML += `<div style="margin: 5px 0; padding: 5px; border-left: 3px solid ${color};">
                <strong>[${time}]</strong> ${message}
            </div>`;
            debugLog.scrollTop = debugLog.scrollHeight;
        }

        function clearDebug() {
            document.getElementById('debugLog').innerHTML = '<p style="color: #999;">Debug log cleared...</p>';
        }

        function showResult(elementId, message, type) {
            const element = document.getElementById(elementId);
            element.className = `result ${type}`;
            element.innerHTML = message;
            element.style.display = 'block';
        }

        function testDatabase() {
            log('Testing database connection...');
            fetch('chat_api.php?action=get_unread_count')
                .then(response => response.json())
                .then(data => {
                    if (data.success !== undefined) {
                        showResult('dbResult', '✅ Database connection successful!', 'success');
                        log('Database connected successfully', 'success');
                    } else {
                        showResult('dbResult', '❌ Unexpected response from database', 'error');
                        log('Unexpected response: ' + JSON.stringify(data), 'error');
                    }
                })
                .catch(error => {
                    showResult('dbResult', '❌ Database connection failed: ' + error.message, 'error');
                    log('Database error: ' + error.message, 'error');
                });
        }

        function testTables() {
            log('Checking chat tables...');
            fetch('test_chat_tables.php')
                .then(response => response.text())
                .then(data => {
                    showResult('tablesResult', data, 'info');
                    log('Tables check completed', 'success');
                })
                .catch(error => {
                    showResult('tablesResult', '❌ Error checking tables: ' + error.message, 'error');
                    log('Tables error: ' + error.message, 'error');
                });
        }

        function testConversation() {
            log('Testing get/create conversation...');
            fetch('chat_api.php?action=get_or_create_conversation')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        testConversationId = data.conversation_id;
                        document.getElementById('convId').textContent = testConversationId;
                        showResult('apiResult', `✅ Conversation initialized! ID: ${testConversationId}`, 'success');
                        log(`Conversation created/retrieved: ${testConversationId}`, 'success');
                    } else {
                        showResult('apiResult', `❌ Failed: ${data.message}`, 'error');
                        log('Conversation error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showResult('apiResult', '❌ Error: ' + error.message, 'error');
                    log('API error: ' + error.message, 'error');
                });
        }

        function testSendMessage() {
            if (!testConversationId) {
                showResult('apiResult', '⚠️ Please initialize conversation first', 'error');
                return;
            }

            const testMsg = 'Test message at ' + new Date().toLocaleTimeString();
            log('Sending test message...');
            
            const formData = new FormData();
            formData.append('conversation_id', testConversationId);
            formData.append('message', testMsg);

            fetch('chat_api.php?action=send_message', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showResult('apiResult', `✅ Message sent successfully!`, 'success');
                    log('Message sent: ' + testMsg, 'success');
                    testGetMessages();
                } else {
                    showResult('apiResult', `❌ Failed to send: ${data.message}`, 'error');
                    log('Send failed: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showResult('apiResult', '❌ Error: ' + error.message, 'error');
                log('Send error: ' + error.message, 'error');
            });
        }

        function testGetMessages() {
            if (!testConversationId) {
                showResult('apiResult', '⚠️ Please initialize conversation first', 'error');
                return;
            }

            log('Fetching messages...');
            fetch(`chat_api.php?action=get_messages&conversation_id=${testConversationId}&last_message_id=0`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showResult('apiResult', `✅ Retrieved ${data.messages.length} messages`, 'success');
                        log(`Retrieved ${data.messages.length} messages`, 'success');
                        displayTestMessages(data.messages);
                    } else {
                        showResult('apiResult', `❌ Failed: ${data.message}`, 'error');
                        log('Get messages error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showResult('apiResult', '❌ Error: ' + error.message, 'error');
                    log('Fetch error: ' + error.message, 'error');
                });
        }

        function testGetConversations() {
            log('Fetching all conversations...');
            fetch('chat_api.php?action=get_conversations')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showResult('apiResult', `✅ Retrieved ${data.conversations.length} conversations`, 'success');
                        log(`Retrieved ${data.conversations.length} conversations`, 'success');
                    } else {
                        showResult('apiResult', `❌ Failed: ${data.message}`, 'error');
                        log('Get conversations error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showResult('apiResult', '❌ Error: ' + error.message, 'error');
                    log('Fetch error: ' + error.message, 'error');
                });
        }

        function sendTestMessage() {
            const input = document.getElementById('testMessageInput');
            const message = input.value.trim();

            if (!message) {
                log('Cannot send empty message', 'error');
                return;
            }

            if (!testConversationId) {
                log('Initializing conversation first...', 'info');
                testConversation();
                setTimeout(() => sendTestMessage(), 1000);
                return;
            }

            log('Sending: ' + message);
            const formData = new FormData();
            formData.append('conversation_id', testConversationId);
            formData.append('message', message);

            fetch('chat_api.php?action=send_message', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    log('Message sent successfully', 'success');
                    refreshMessages();
                } else {
                    log('Failed to send: ' + data.message, 'error');
                }
            })
            .catch(error => {
                log('Send error: ' + error.message, 'error');
            });
        }

        function refreshMessages() {
            if (!testConversationId) {
                log('No active conversation', 'error');
                return;
            }

            fetch(`chat_api.php?action=get_messages&conversation_id=${testConversationId}&last_message_id=${lastTestMessageId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.messages.length > 0) {
                        displayTestMessages(data.messages);
                        lastTestMessageId = data.messages[data.messages.length - 1].id;
                        log(`Loaded ${data.messages.length} new messages`, 'success');
                    }
                })
                .catch(error => {
                    log('Refresh error: ' + error.message, 'error');
                });
        }

        function displayTestMessages(messages) {
            const chatBox = document.getElementById('testChatBox');
            
            if (messages.length === 0) {
                chatBox.innerHTML = '<p style="text-align: center; color: #999;">No messages yet</p>';
                return;
            }

            // Clear only if first load
            if (lastTestMessageId === 0) {
                chatBox.innerHTML = '';
            }

            messages.forEach(msg => {
                const isMe = msg.role === '<?= $role ?>';
                const msgDiv = document.createElement('div');
                msgDiv.className = `message ${isMe ? 'sent' : ''}`;
                msgDiv.innerHTML = `
                    <strong>${msg.firstname} ${msg.lastname} (${msg.role})</strong><br>
                    ${escapeHtml(msg.message)}
                    <div class="message-time">${new Date(msg.created_at).toLocaleTimeString()}</div>
                `;
                chatBox.appendChild(msgDiv);
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function clearTestData() {
            if (!confirm('Are you sure you want to clear test conversations? This cannot be undone.')) {
                return;
            }

            log('Clearing test data...');
            fetch('clear_test_chats.php', {method: 'POST'})
                .then(response => response.text())
                .then(data => {
                    showResult('cleanupResult', data, 'success');
                    log('Test data cleared', 'success');
                    testConversationId = null;
                    lastTestMessageId = 0;
                    document.getElementById('convId').textContent = 'Not initialized';
                    document.getElementById('testChatBox').innerHTML = '<p style="text-align: center; color: #999;">No messages yet</p>';
                })
                .catch(error => {
                    showResult('cleanupResult', '❌ Error: ' + error.message, 'error');
                    log('Cleanup error: ' + error.message, 'error');
                });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Auto-initialize
        window.addEventListener('load', function() {
            log('Test page loaded', 'success');
            log('User: <?= $username ?> (<?= $role ?>)', 'info');
            testConversation();
            
            // Auto-refresh messages every 3 seconds
            setInterval(refreshMessages, 3000);
        });
    </script>
</body>
</html>
