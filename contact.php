<?php
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['username']) && isset($_SESSION['role']);
$role = $isLoggedIn ? $_SESSION['role'] : '';
$fullname = '';

if ($isLoggedIn) {
    include 'config.php';
    
    // Get user_id if not set in session
    if (!isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
        }
    }
    
    $stmt = $pdo->prepare("SELECT firstname, middlename, lastname FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $fullname = trim($user['firstname'] . ' ' . $user['middlename'] . ' ' . $user['lastname']);
    } else {
        $fullname = $_SESSION['username'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Mae's Bridal Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fdfdfd;
            color: #333;
            padding-top: 70px;
        }

        /* Navbar Styles */
        .navbar-custom {
            background: linear-gradient(135deg, #d6336c, #e76f51);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }
        .nav-link {
            color: white !important;
            margin: 0 10px;
        }
        .nav-link:hover {
            color: #ffd700 !important;
        }
        .btn-contact {
            background-color: #fff;
            color: #d6336c;
            border: none;
        }
        .btn-contact:hover {
            background-color: #ffd700;
            color: #d6336c;
        }

        .page-header {
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: white;
            padding: 40px 20px;
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .page-header p {
            margin: 10px 0 0 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .contact-card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .contact-info {
            margin-bottom: 30px;
        }

        .contact-info h2 {
            color: #d6336c;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .contact-info p {
            font-size: 16px;
            margin: 12px 0;
        }

        .contact-info i {
            color: #d6336c;
            margin-right: 10px;
            width: 20px;
        }

        .map-container {
            margin-bottom: 40px;
        }

        .map-container iframe {
            width: 100%;
            height: 350px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .contact-form h2 {
            margin-bottom: 20px;
            color: #d6336c;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #d6336c;
        }

        .form-group textarea {
            height: 120px;
            resize: vertical;
        }

        .submit-btn {
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(214, 51, 108, 0.3);
        }

        .success-message {
            margin-top: 20px;
            padding: 15px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: white;
            margin-top: 60px;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            .container {
                margin: 20px;
                padding: 15px;
            }
        }

        /* Chat Styles */
        .chat-container-client {
            display: flex;
            flex-direction: column;
            height: 600px;
        }

        .chat-container-client h2 {
            margin-bottom: 20px;
            color: #d6336c;
            font-size: 1.5rem;
        }

        .chat-box {
            flex: 1;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            overflow-y: auto;
            background: #f9f9f9;
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .empty-chat-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #999;
            text-align: center;
        }

        .empty-chat-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d6336c;
        }

        .chat-message {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            max-width: 75%;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-message.sent {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .chat-message.received {
            align-self: flex-start;
        }

        .chat-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .chat-bubble {
            background: white;
            padding: 12px 16px;
            border-radius: 18px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            word-wrap: break-word;
        }

        .chat-message.sent .chat-bubble {
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: white;
        }

        .chat-time {
            font-size: 0.7rem;
            color: #999;
            margin-top: 5px;
        }

        .chat-input-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .chat-input-field {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .chat-input-field:focus {
            outline: none;
            border-color: #d6336c;
        }

        .chat-send-btn {
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            white-space: nowrap;
        }

        .chat-send-btn:hover {
            transform: scale(1.05);
        }

        .login-prompt {
            text-align: center;
            padding: 40px 20px;
        }

        .login-prompt h3 {
            color: #333;
            margin: 20px 0 10px;
        }

        .login-prompt p {
            color: #666;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="<?= $isLoggedIn ? ($role === 'client' ? 'dashboard_client.php' : 'dashboard_admin.php') : 'login.php' ?>">
        <i class="fas fa-ring"></i> Mae's Bridal Shop
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background-color: white;">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($isLoggedIn && $role === 'client'): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard_client.php#hero">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="booking_history.php">Booking History</a></li>
          <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <?php elseif ($isLoggedIn && $role === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if ($isLoggedIn): ?>
          <span class="text-white"><i class="fas fa-user"></i> <?= htmlspecialchars($fullname) ?></span>
          <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-sm btn-outline-light">Login</a>
          <a href="register.php" class="btn btn-sm btn-contact">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="fas fa-envelope"></i> Contact Us</h1>
    <p>We'd love to hear from you! Get in touch with Mae's Bridal Shop</p>
</div>

<div class="container">

    <div class="contact-card">
        <div class="contact-info">
            <h2><i class="fas fa-map-marker-alt"></i> Visit Us</h2>
            <p><i class="fas fa-map-pin"></i> <strong>Location:</strong> Sampaguita, Solana, Cagayan</p>
            <p><i class="fas fa-phone-alt"></i> <strong>Phone:</strong> 0912-345-6789</p>
            <p><i class="fas fa-envelope"></i> <strong>Email:</strong> maebridalevents@example.com</p>
        </div>

        <!-- Google Map Embed -->
        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3895.631821764137!2d121.6625!3d17.6517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3385513a7d1f5edb%3A0x31d0c0a07b5d6263!2sSampaguita%2C%20Solana%2C%20Cagayan!5e0!3m2!1sen!2sph!4v1629559156164!5m2!1sen!2sph"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

    <?php if ($isLoggedIn && $role === 'client'): ?>
    <!-- Live Chat for Logged-in Clients -->
    <div class="contact-card">
        <div class="chat-container-client">
            <h2><i class="fas fa-comments"></i> Live Chat with Admin</h2>
            <div class="chat-box" id="chatBox">
                <div class="empty-chat-state">
                    <i class="fas fa-comments"></i>
                    <p>Start a conversation with our admin team</p>
                </div>
            </div>
            <div class="chat-input-container">
                <input type="text" id="chatInput" class="chat-input-field" placeholder="Type your message here..." onkeypress="handleChatKeyPress(event)">
                <button onclick="sendChatMessage()" class="chat-send-btn">
                    <i class="fas fa-paper-plane"></i> Send
                </button>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Login Prompt for Non-logged-in Users -->
    <div class="contact-card">
        <div class="contact-form">
            <h2><i class="fas fa-comments"></i> Live Chat</h2>
            <div class="login-prompt">
                <i class="fas fa-lock" style="font-size: 3rem; color: #d6336c; margin-bottom: 1rem;"></i>
                <h3>Please Login to Use Live Chat</h3>
                <p>To chat with our admin team in real-time, please log in to your account.</p>
                <a href="login.php" class="submit-btn" style="display: inline-block; text-decoration: none; margin-top: 1rem;">
                    <i class="fas fa-sign-in-alt"></i> Login Now
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<footer>
    <p><i class="fas fa-heart"></i> &copy; <?php echo date("Y"); ?> Mae's Bridal Shop | Sampaguita, Solana, Cagayan</p>
    <p>Making Your Special Day Memorable</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($isLoggedIn && $role === 'client'): ?>
<script>
    let conversationId = null;
    let lastMessageId = 0;
    let messageCheckInterval = null;

    // Initialize chat on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeChat();
    });

    function initializeChat() {
        // Get or create conversation
        fetch('chat_api.php?action=get_or_create_conversation')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    conversationId = data.conversation_id;
                    loadMessages();
                    // Start polling for new messages every 2 seconds
                    messageCheckInterval = setInterval(loadMessages, 2000);
                }
            })
            .catch(error => console.error('Error initializing chat:', error));
    }

    function loadMessages() {
        if (!conversationId) return;
        
        fetch(`chat_api.php?action=get_messages&conversation_id=${conversationId}&last_message_id=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages.length > 0) {
                    displayMessages(data.messages);
                    lastMessageId = data.messages[data.messages.length - 1].id;
                }
            })
            .catch(error => console.error('Error loading messages:', error));
    }

    function displayMessages(messages) {
        const chatBox = document.getElementById('chatBox');
        
        // Remove empty state if present
        const emptyState = chatBox.querySelector('.empty-chat-state');
        if (emptyState) {
            emptyState.remove();
        }
        
        messages.forEach(msg => {
            // Check if message already exists (avoid duplicates)
            if (document.querySelector(`[data-message-id="${msg.id}"]`)) {
                return; // Skip this message, it's already displayed
            }
            
            const isClient = msg.role === 'client';
            const messageDiv = document.createElement('div');
            messageDiv.className = `chat-message ${isClient ? 'sent' : 'received'}`;
            messageDiv.setAttribute('data-message-id', msg.id); // Add unique identifier
            
            // Handle empty names
            const firstname = msg.firstname || 'Admin';
            const lastname = msg.lastname || '';
            const initials = firstname && lastname ? (firstname[0] + lastname[0]).toUpperCase() : firstname[0].toUpperCase();
            const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            messageDiv.innerHTML = `
                <div class="chat-avatar">${initials}</div>
                <div>
                    <div class="chat-bubble">${escapeHtml(msg.message)}</div>
                    <div class="chat-time">${time}</div>
                </div>
            `;
            
            chatBox.appendChild(messageDiv);
        });
        
        // Scroll to bottom
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function sendChatMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        
        if (!message || !conversationId) {
            console.error('Cannot send message:', {message, conversationId});
            return;
        }
        
        console.log('Sending message:', message, 'to conversation:', conversationId);
        
        const formData = new FormData();
        formData.append('conversation_id', conversationId);
        formData.append('message', message);
        
        fetch('chat_api.php?action=send_message', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Send response:', data);
            if (data.success) {
                input.value = '';
                loadMessages();
            } else {
                console.error('Failed to send message:', data.message);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    }

    function handleChatKeyPress(event) {
        if (event.key === 'Enter') {
            sendChatMessage();
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Clean up interval when page is unloaded
    window.addEventListener('beforeunload', function() {
        if (messageCheckInterval) {
            clearInterval(messageCheckInterval);
        }
    });
</script>
<?php endif; ?>

</body>
</html>
